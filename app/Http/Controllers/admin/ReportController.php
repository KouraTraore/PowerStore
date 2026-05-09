<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Afficher la liste des factures
    public function index()
    {
        $factures = Facture::with('client')->get();
        return view('admin.factures', compact('factures'));
    }

    // Afficher le formulaire d'ajout
    public function create()
    {
        return view('admin.add_facture');
    }

    // Enregistrer une nouvelle facture
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'montant_total' => 'required|numeric',
            'statut' => 'required|in:Payé,Non payé'
        ]);

        Facture::create([
            'client_id' => $request->client_id,
            'commande_id' => $request->commande_id,
            'montant_total' => $request->montant_total,
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.factures.index')
                         ->with('success', 'Facture ajoutée avec succès !');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $facture = Facture::findOrFail($id);
        return view('admin.edit_facture', compact('facture'));
    }

    // Mettre à jour une facture
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'montant_total' => 'required|numeric',
            'statut' => 'required|in:Payé,Non payé'
        ]);

        $facture = Facture::findOrFail($id);
        $facture->update([
            'client_id' => $request->client_id,
            'commande_id' => $request->commande_id,
            'montant_total' => $request->montant_total,
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.factures.index')
                         ->with('success', 'Facture modifiée avec succès !');
    }

    // Supprimer une facture (GET)
    public function delete($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();

        return redirect()->route('admin.factures.index')
                         ->with('success', 'Facture supprimée avec succès !');
    }

    // Supprimer une facture (DELETE)
    public function destroy($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();

        return redirect()->route('admin.factures.index')
                         ->with('success', 'Facture supprimée avec succès !');
    }
}