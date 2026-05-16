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
    // Liste des factures
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

    // Formulaire d'ajout
    public function create()
    {
        $clients = Client::all();
        return view('admin.factures.add_facture', compact('clients'));
    }

    // Enregistrement d'une nouvelle facture
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

        $data = $request->only(['client_id', 'montant_total', 'mode_paiement', 'date_paiement', 'reference_paiement']);
        $montantPaye = $request->input('montant_paye', 0);
        $data['montant_paye'] = $montantPaye;
        $data['reste_a_payer'] = $data['montant_total'] - $montantPaye;

        if ($montantPaye >= $data['montant_total']) {
            $data['statut'] = 'Payé';
            $data['etatf'] = 1;
        } elseif ($montantPaye > 0) {
            $data['statut'] = 'Partiel';
            $data['etatf'] = 2;
        } else {
            $data['statut'] = 'Non payé';
            $data['etatf'] = 0;
        }

        $data['nomf'] = 'FACT-' . date('Ymd') . '-' . rand(1000, 9999);
        $data['datef'] = now();

        Facture::create($data);

        return redirect()->route('admin.factures.index')->with('success', '✅ Facture créée avec succès !');
    }

    // Détail d'une facture
    public function show($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        return view('admin.factures.show', compact('facture'));
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $facture = Facture::findOrFail($id);
        $clients = Client::all();
        return view('admin.factures.edit_facture', compact('facture', 'clients'));
    }

    // Mise à jour d'une facture
    public function update(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant_total' => 'required|numeric|min:0',
            'montant_paye' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['client_id', 'montant_total', 'mode_paiement', 'date_paiement', 'reference_paiement']);
        $montantPaye = $request->input('montant_paye', 0);
        $data['montant_paye'] = $montantPaye;
        $data['reste_a_payer'] = $data['montant_total'] - $montantPaye;

        if ($montantPaye >= $data['montant_total']) {
            $data['statut'] = 'Payé';
            $data['etatf'] = 1;
        } elseif ($montantPaye > 0) {
            $data['statut'] = 'Partiel';
            $data['etatf'] = 2;
        } else {
            $data['statut'] = 'Non payé';
            $data['etatf'] = 0;
        }

        $facture->update($data);

        return redirect()->route('admin.factures.index')->with('success', '✅ Facture modifiée avec succès !');
    }

    // Suppression d'une facture
    public function delete($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();
        return redirect()->route('admin.factures.index')->with('success', '🗑️ Facture supprimée avec succès !');
    }

    // Export PDF d'une facture
    public function exportPdf($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        $pdf = Pdf::loadView('admin.factures.factures_pdf', compact('facture'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('FACTURE_'.$facture->id.'_'.date('d-m-Y').'.pdf');
    }

    // Impression directe (sans PDF)
    public function printView($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        return view('admin.factures.print', compact('facture'));
    }

    // Export PDF de toutes les factures
    public function exportAllPdf()
    {
        $factures = Facture::with('client')->get();
        $stats = ['total' => $factures->sum('montant_total'), 'date' => date('d/m/Y')];
        $pdf = Pdf::loadView('admin.factures.factures_liste_pdf', compact('factures', 'stats'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('TOUTES_FACTURES_'.date('d-m-Y').'.pdf');
    }

    // Envoi d'email
    public function sendEmail($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        if (!$facture->client || !$facture->client->email) {
            return redirect()->back()->with('error', '❌ Ce client n\'a pas d\'adresse email !');
        }
        $pdf = Pdf::loadView('admin.factures.factures_pdf', compact('facture'));
        Mail::send('admin.factures.facture_email', ['facture' => $facture], function($message) use ($facture, $pdf) {
            $message->to($facture->client->email)
                    ->subject('Facture POWERSTOCK N° '.$facture->id)
                    ->attachData($pdf->output(), 'Facture_'.$facture->id.'.pdf');
        });
        $facture->update(['email_envoye' => 'Oui', 'date_envoi_email' => now()]);
        return redirect()->back()->with('success', '📧 Facture envoyée par email avec succès !');
    }


}