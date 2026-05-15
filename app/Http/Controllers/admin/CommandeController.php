<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Product;
use App\Models\Client;
use App\Models\Facture;  // ← IMPORT MANQUANT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    // Affiche la liste des commandes
    public function index()
    {
        $commandes = Commande::with(['client', 'facture'])->orderBy('date_commande', 'desc')->get();
        $stats = [
            'total'      => $commandes->count(),
            'livrees'    => $commandes->where('statut', 'livree')->count(),
            'en_attente' => $commandes->where('statut', 'en_attente')->count(),
            'annulees'   => $commandes->where('statut', 'annulee')->count(),
        ];
        return view('admin.commandes.index', compact('commandes', 'stats'));
    }

    // Formulaire de création
    public function create()
    {
        $clients = Client::all();
        $produits = Product::all();
        return view('admin.commandes.create', compact('clients', 'produits'));
    }

    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'produits'    => 'required|array|min:1',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.qty' => 'required|integer|min:1',
        ]);

        $produitsData = $request->produits;
        $total = 0;

        foreach ($produitsData as $item) {
            $produit = Product::find($item['id']);
            if ($produit->quantite < $item['qty']) {
                return back()->withErrors(['stock' => "Stock insuffisant pour {$produit->nomp}"])->withInput();
            }
            $total += $produit->prix * $item['qty'];
        }

        DB::transaction(function () use ($request, $produitsData, $total) {
            $commande = Commande::create([
                'client_id'      => $request->client_id,
                'date_commande'  => $request->date_commande,
                'statut'         => 'en_attente',
                'total_ttc'      => $total,
                'created_by'     => Auth::id(),
            ]);

            foreach ($produitsData as $item) {
                $produit = Product::find($item['id']);
                DetailCommande::create([
                    'commande_id'    => $commande->id,
                    'produit_id'     => $item['id'],
                    'quantite'       => $item['qty'],
                    'prix_unitaire'  => $produit->prix,
                ]);
                $produit->decrement('quantite', $item['qty']);
            }
        });

        return redirect()->route('admin.commandes.index')->with('success', 'Commande ajoutée avec succès.');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $commande = Commande::with('details')->findOrFail($id);
        $clients = Client::all();
        $produits = Product::all();
        return view('admin.commandes.edit', compact('commande', 'clients', 'produits'));
    }

    public function update(Request $request, $id)
{
    $commande = Commande::findOrFail($id);

    $request->validate([
        'client_id'   => 'required|exists:clients,id',
        'date_commande' => 'required|date',
        'statut'      => 'required|in:en_attente,livree,annulee', // validation du statut
        'produits'    => 'required|array|min:1',
        'produits.*.id' => 'required|exists:produits,id',
        'produits.*.qty' => 'required|integer|min:1',
    ]);

    $produitsData = $request->produits;
    $total = 0;

    DB::transaction(function () use ($commande, $request, $produitsData, &$total) {
        // Restaurer l'ancien stock
        foreach ($commande->details as $oldDetail) {
            $oldDetail->produit->increment('quantite', $oldDetail->quantite);
        }
        // Supprimer les anciens détails
        $commande->details()->delete();

        // Vérifier le stock pour les nouveaux produits
        foreach ($produitsData as $item) {
            $produit = Product::find($item['id']);
            if ($produit->quantite < $item['qty']) {
                throw new \Exception("Stock insuffisant pour {$produit->nomp}");
            }
            $total += $produit->prix * $item['qty'];
        }

        // Créer les nouveaux détails
        foreach ($produitsData as $item) {
            $produit = Product::find($item['id']);
            DetailCommande::create([
                'commande_id'    => $commande->id,
                'produit_id'     => $item['id'],
                'quantite'       => $item['qty'],
                'prix_unitaire'  => $produit->prix,
            ]);
            $produit->decrement('quantite', $item['qty']);
        }

        // Mettre à jour la commande (y compris le statut)
        $commande->update([
            'client_id'     => $request->client_id,
            'date_commande' => $request->date_commande,
            'total_ttc'     => $total,
            'statut'        => $request->statut,
        ]);

        // Si le nouveau statut n'est pas "livree", on supprime la facture associée si elle existe
        if ($request->statut != 'livree' && $commande->facture_id) {
            $commande->facture()->delete();
            $commande->facture_id = null;
            $commande->save();
        }
    });

    return redirect()->route('admin.commandes.index')->with('success', 'Commande modifiée avec succès.');
}
    // Supprimer une commande
    public function delete($id)
    {
        $commande = Commande::findOrFail($id);

        DB::transaction(function () use ($commande) {
            foreach ($commande->details as $detail) {
                $detail->produit->increment('quantite', $detail->quantite);
            }
            $commande->details()->delete();
            $commande->delete();
        });

        return redirect()->route('admin.commandes.index')->with('success', 'Commande supprimée avec succès.');
    }

    // Afficher les détails d'une commande
    public function show($id)
    {
        $commande = Commande::with(['client', 'details.produit', 'facture'])->findOrFail($id);
        return view('admin.commandes.show', compact('commande'));
    }

    // Changer le statut d'une commande
    public function changeStatut(Request $request, $id, $statut)
    {
        $commande = Commande::findOrFail($id);
        $ancien = $commande->statut;

        if ($ancien == 'en_attente' && $statut == 'livree') {
            return redirect()->route('admin.commandes.choix-facture', $id);
        }

        if ($ancien == 'livree' && $statut != 'livree') {
            DB::transaction(function () use ($commande) {
                if ($commande->facture_id) {
                    $commande->facture()->delete();
                    $commande->facture_id = null;
                    $commande->save();
                }
            });
        }

        $commande->statut = $statut;
        $commande->save();

        return redirect()->route('admin.commandes.index')->with('success', 'Statut de la commande mis à jour.');
    }

    // Afficher la page de choix de facture avant livraison
    public function choixFactureLivraison($id)
    {
        $commande = Commande::with('client')->findOrFail($id);
        // Récupérer les factures existantes non payées du même client
        $facturesExistantes = Facture::whereHas('commandes', function ($q) use ($commande) {
            $q->where('client_id', $commande->client_id);
        })->where('etatf', 0)->get();

        return view('admin.commandes.choix_facture', compact('commande', 'facturesExistantes'));
    }

    public function livrerAvecFacture(Request $request, $id)
{
    $commande = Commande::findOrFail($id);
    $option = $request->option; // 'existing', 'new', 'none'
    $facture_id = $request->facture_id;

    DB::transaction(function () use ($commande, $option, $facture_id) {
        if ($option == 'existing' && $facture_id) {
            // Associer à une facture existante
            $commande->facture_id = $facture_id;
        } elseif ($option == 'new') {
            // Créer une nouvelle facture avec les bonnes colonnes
            $dernierId = Facture::max('id') + 1;
            $numero = 'FACT-' . date('Ymd') . '-' . str_pad($dernierId, 4, '0', STR_PAD_LEFT);
            
            $facture = Facture::create([
                'client_id'      => $commande->client_id,        // obligatoire
                'nomf'           => $numero,                     // numéro facture
                'montant_total'  => $commande->total_ttc,        // montant total
                'statut'         => 'Non payé',                  // statut par défaut
                'montant_paye'   => 0,
                'reste_a_payer'  => $commande->total_ttc,
                'datef'          => now(),
                'etatf'          => 0,
            ]);
            $commande->facture_id = $facture->id;
        }
        // option 'none' → pas de facture
        $commande->statut = 'livree';
        $commande->save();
    });

    if ($option == 'new' || ($option == 'existing' && $facture_id)) {
        $factureId = ($option == 'new') ? $commande->facture_id : $facture_id;
        return redirect()->route('admin.factures.show', $factureId)->with('success', 'Commande livrée et facture associée.');
    } else {
        return redirect()->route('admin.commandes.show', $commande->id)->with('success', 'Commande livrée sans facture.');
    }
}
    // Impression de la commande (vue pour impression)
    public function printView($id)
    {
        $commande = Commande::with(['client', 'details.produit'])->findOrFail($id);
        return view('admin.commandes.print', compact('commande'));
    }
}