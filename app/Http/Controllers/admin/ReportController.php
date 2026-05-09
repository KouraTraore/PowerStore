<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $factures = Facture::all();

        return view('admin.factures', compact('factures'));
    }

    public function create()
    {
        return view('admin.add_facture');
    }

    public function store(Request $request)
    {
        Facture::create([
            'client_id' => $request->client_id,
            'commande_id' => $request->commande_id,
            'montant_total' => $request->montant_total,
            'statut' => $request->statut
        ]);

        return redirect()->route('admin.factures');
    }

    public function edit($id)
    {
        $facture = Facture::findOrFail($id);

        return view('admin.edit_facture', compact('facture'));
    }

    public function update(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);

        $facture->update([
            'client_id' => $request->client_id,
            'commande_id' => $request->commande_id,
            'montant_total' => $request->montant_total,
            'statut' => $request->statut
        ]);

        return redirect()->route('admin.factures');
    }

    public function delete($id)
    {
        $facture = Facture::findOrFail($id);

        $facture->delete();

        return redirect()->route('admin.factures');
    }
}