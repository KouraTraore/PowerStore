<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Client;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function index()
    {
        $factures = Facture::with('client')->latest('datef')->get();
        $stats = [
            'total' => $factures->count(),
            'paye' => $factures->where('etatf', 1)->count(),
            'non_paye' => $factures->where('etatf', 0)->count(),
            'partiel' => $factures->where('etatf', 2)->count(),
            'montant_total' => $factures->sum('montant_total'),
            'montant_paye' => $factures->sum('montant_paye'),
            'reste_total' => $factures->sum('reste_a_payer')
        ];
        return view('admin.factures.index', compact('factures', 'stats'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('admin.factures.add_facture', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant_total' => 'required|numeric|min:0',
            'montant_paye' => 'nullable|numeric|min:0',
            'mode_paiement' => 'nullable|string',
            'date_paiement' => 'nullable|date',
            'reference_paiement' => 'nullable|string'
        ]);

        $clientId = $request->input('client_id');
        $montantTotal = $request->input('montant_total');
        $montantPaye = $request->input('montant_paye', 0);
        $resteAPayer = $montantTotal - $montantPaye;

        if ($montantPaye >= $montantTotal) {
            $statut = 'Payé';
            $etatf = 1;
        } elseif ($montantPaye > 0) {
            $statut = 'Partiel';
            $etatf = 2;
        } else {
            $statut = 'Non payé';
            $etatf = 0;
        }

        $facture = Facture::create([
            'client_id' => $clientId,
            'montant_total' => $montantTotal,
            'montant_paye' => $montantPaye,
            'reste_a_payer' => $resteAPayer,
            'statut' => $statut,
            'etatf' => $etatf,
            'mode_paiement' => $request->input('mode_paiement'),
            'date_paiement' => $request->input('date_paiement'),
            'reference_paiement' => $request->input('reference_paiement'),
            'nomf' => 'FACT-' . date('Ymd') . '-' . rand(1000, 9999),
            'datef' => now(),
        ]);

        return redirect()->route('admin.factures.index')->with('success', 'Facture créée avec succès.');
    }

    public function show($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        return view('admin.factures.show', compact('facture'));
    }

    public function edit($id)
    {
        $facture = Facture::findOrFail($id);
        $clients = Client::all();
        return view('admin.factures.edit_facture', compact('facture', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant_total' => 'required|numeric|min:0',
            'montant_paye' => 'nullable|numeric|min:0',
        ]);

        $clientId = $request->input('client_id');
        $montantTotal = $request->input('montant_total');
        $montantPaye = $request->input('montant_paye', 0);
        $resteAPayer = $montantTotal - $montantPaye;

        if ($montantPaye >= $montantTotal) {
            $statut = 'Payé';
            $etatf = 1;
        } elseif ($montantPaye > 0) {
            $statut = 'Partiel';
            $etatf = 2;
        } else {
            $statut = 'Non payé';
            $etatf = 0;
        }

        $facture->update([
            'client_id' => $clientId,
            'montant_total' => $montantTotal,
            'montant_paye' => $montantPaye,
            'reste_a_payer' => $resteAPayer,
            'statut' => $statut,
            'etatf' => $etatf,
            'mode_paiement' => $request->input('mode_paiement'),
            'date_paiement' => $request->input('date_paiement'),
            'reference_paiement' => $request->input('reference_paiement'),
        ]);

        return redirect()->route('admin.factures.index')->with('success', 'Facture modifiée.');
    }

    public function delete($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();
        return redirect()->route('admin.factures.index')->with('success', 'Facture supprimée.');
    }

    public function exportPdf($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        $pdf = Pdf::loadView('admin.factures.factures_pdf', compact('facture'));
        return $pdf->download('facture_'.$id.'.pdf');
    }

    public function exportAllPdf()
    {
        $factures = Facture::with('client')->get();
        $pdf = Pdf::loadView('admin.factures.factures_liste_pdf', compact('factures'));
        return $pdf->download('toutes_factures.pdf');
    }

    public function sendEmail($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        if (!$facture->client || !$facture->client->email) {
            return redirect()->back()->with('error', 'Client sans email.');
        }
        $pdf = Pdf::loadView('admin.factures.factures_pdf', compact('facture'));
        Mail::send('admin.factures.facture_email', ['facture' => $facture], function($m) use ($facture, $pdf) {
            $m->to($facture->client->email)->subject('Facture '.$facture->nomf)->attachData($pdf->output(), 'facture.pdf');
        });
        return redirect()->back()->with('success', 'Email envoyé.');
    }

    public function paiementsDashboard()
    {
        $factures = Facture::with('client')->get();
        $paiementsParMode = [
            'Espèces' => $factures->where('mode_paiement', 'Espèces')->sum('montant_paye'),
            'Carte' => $factures->where('mode_paiement', 'Carte')->sum('montant_paye'),
            'Mobile Money' => $factures->where('mode_paiement', 'Mobile Money')->sum('montant_paye'),
            'Virement' => $factures->where('mode_paiement', 'Virement')->sum('montant_paye'),
        ];
        return view('admin.factures.paiements_dashboard', compact('paiementsParMode', 'factures'));
    }
}