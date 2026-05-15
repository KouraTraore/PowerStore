<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    // Formulaire d'enregistrement d'un paiement (depuis une facture)
    public function create($facture_id)
    {
        $facture = Facture::with('commande.client')->findOrFail($facture_id);
        return view('admin.paiements.create', compact('facture'));
    }

    // Enregistrer un paiement
    public function store(Request $request, $facture_id)
    {
        $facture = Facture::findOrFail($facture_id);

        $request->validate([
            'montant' => 'required|numeric|min:0.01|max:' . $facture->montant_total,
            'mode'    => 'required|in:especes,carte,mobile_money,virement',
            'date_paiement' => 'required|date',
        ]);

        // Générer une référence unique
        $reference = 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid());

        DB::transaction(function () use ($facture, $request, $reference) {
            // Créer le paiement
            Paiement::create([
                'facture_id'    => $facture->id,
                'montant'       => $request->montant,
                'reference'     => $reference,
                'date_paiement' => $request->date_paiement,
                'mode'          => $request->mode,
            ]);

            // Mettre à jour le statut de la facture
            $totalPaye = $facture->paiement()->sum('montant') + $request->montant;
            if ($totalPaye >= $facture->montant_total) {
                $facture->etatf = 1; // Payée
            } else {
                $facture->etatf = 0; // Non payée (ou partiel – on garde 0)
            }
            $facture->save();
        });

        return redirect()->route('admin.factures.show', $facture->id)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    // Historique des paiements (optionnel)
    public function index()
    {
        $paiements = Paiement::with('facture.commande.client')->orderBy('created_at', 'desc')->get();
        return view('admin.paiements.index', compact('paiements'));
    }
}