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
    // Afficher la liste des factures
    public function index()
    {
        $factures = Facture::with('client')->latest()->get();
        
        // Statistiques pour le dashboard
        $stats = [
            'total' => $factures->count(),
            'paye' => $factures->where('statut', 'Payé')->count(),
            'non_paye' => $factures->where('statut', 'Non payé')->count(),
            'partiel' => $factures->where('statut', 'Partiel')->count(),
            'montant_total' => $factures->sum('montant_total'),
            'montant_paye' => $factures->sum('montant_paye'),
            'reste_total' => $factures->sum('reste_a_payer')
        ];
        
        return view('admin.factures', compact('factures', 'stats'));
    }

    // Afficher le formulaire d'ajout
    public function create()
    {
        $clients = Client::all();
        return view('admin.add_facture', compact('clients'));
    }

    // Enregistrer une nouvelle facture
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant_total' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string',
            'montant_paye' => 'nullable|numeric|min:0'
        ]);
        
        $data = $request->all();
        $data['reste_a_payer'] = $data['montant_total'] - ($data['montant_paye'] ?? 0);
        
        if ($data['montant_paye'] >= $data['montant_total']) {
            $data['statut'] = 'Payé';
        } elseif ($data['montant_paye'] > 0) {
            $data['statut'] = 'Partiel';
        } else {
            $data['statut'] = 'Non payé';
        }
        
        Facture::create($data);
        
        return redirect()->route('admin.factures.index')
                         ->with('success', '✅ Facture créée avec succès !');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $facture = Facture::findOrFail($id);
        $clients = Client::all();
        return view('admin.edit_facture', compact('facture', 'clients'));
    }

    // Mettre à jour une facture
    public function update(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);
        
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'montant_total' => 'required|numeric|min:0',
            'montant_paye' => 'nullable|numeric|min:0'
        ]);
        
        $data = $request->all();
        $data['reste_a_payer'] = $data['montant_total'] - ($data['montant_paye'] ?? 0);
        
        if ($data['montant_paye'] >= $data['montant_total']) {
            $data['statut'] = 'Payé';
        } elseif ($data['montant_paye'] > 0) {
            $data['statut'] = 'Partiel';
        } else {
            $data['statut'] = 'Non payé';
        }
        
        $facture->update($data);
        
        return redirect()->route('admin.factures.index')
                         ->with('success', '✅ Facture modifiée avec succès !');
    }

    // Supprimer une facture
    public function delete($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();
        
        return redirect()->route('admin.factures.index')
                         ->with('success', '🗑️ Facture supprimée avec succès !');
    }

    // Exporter une facture en PDF
    public function exportPdf($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        $pdf = Pdf::loadView('admin.factures_pdf', compact('facture'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('FACTURE_'.$facture->id.'_'.date('d-m-Y').'.pdf');
    }

    // Exporter toutes les factures en PDF
    public function exportAllPdf()
    {
        $factures = Facture::with('client')->get();
        $stats = [
            'total' => $factures->sum('montant_total'),
            'date' => date('d/m/Y')
        ];
        
        $pdf = Pdf::loadView('admin.factures_liste_pdf', compact('factures', 'stats'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('TOUTES_FACTURES_'.date('d-m-Y').'.pdf');
    }

    // Envoyer la facture par email
    public function sendEmail($id)
    {
        $facture = Facture::with('client')->findOrFail($id);
        
        if (!$facture->client || !$facture->client->email) {
            return redirect()->back()->with('error', '❌ Ce client n\'a pas d\'adresse email !');
        }
        
        // Générer le PDF
        $pdf = Pdf::loadView('admin.factures_pdf', compact('facture'));
        
        // Envoyer l'email
        Mail::send('admin.facture_email', ['facture' => $facture], function($message) use ($facture, $pdf) {
            $message->to($facture->client->email)
                    ->subject('Facture POWERSTOCK N° '.$facture->id)
                    ->attachData($pdf->output(), 'Facture_'.$facture->id.'.pdf');
        });
        
        // Mettre à jour le statut d'envoi
        $facture->update([
            'email_envoye' => 'Oui',
            'date_envoi_email' => now()
        ]);
        
        return redirect()->back()->with('success', '📧 Facture envoyée par email avec succès !');
    }

    // Tableau de bord des paiements
    public function paiementsDashboard()
    {
        $factures = Facture::with('client')->get();
        
        // Paiements par mode
        $paiementsParMode = [
            'Espèces' => $factures->where('mode_paiement', 'Espèces')->sum('montant_paye'),
            'Carte' => $factures->where('mode_paiement', 'Carte')->sum('montant_paye'),
            'Mobile Money' => $factures->where('mode_paiement', 'Mobile Money')->sum('montant_paye'),
            'Virement' => $factures->where('mode_paiement', 'Virement')->sum('montant_paye'),
        ];
        
        // Paiements par mois
        $paiementsParMois = $factures->groupBy(function($facture) {
            return $facture->date_paiement ? $facture->date_paiement->format('F Y') : 'Non payé';
        })->map(function($group) {
            return $group->sum('montant_paye');
        });
        
        return view('admin.paiements_dashboard', compact('paiementsParMode', 'paiementsParMois', 'factures'));
    }
}