<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $request->get('order', 'asc');
        $search = $request->get('search', '');

        // Liste paginée avec relations Eloquent
        $clients = Client::withCount('commandes')  // nb_commandes
            ->withSum('commandes', 'total_ttc')     // total_achats
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('prenom', $order)
            ->paginate(10);

        // Statistiques avec Eloquent
        $totalClients = Client::count();
        $clientsActifs = Commande::distinct('client_id')->count('client_id');
        $caTotal = Commande::sum('total_ttc');

        return view('admin.clients.index', compact(
            'clients',
            'totalClients',
            'clientsActifs',
            'caTotal',
            'order',
            'search'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:50',
            'nomc'   => 'nullable|string|max:50',
            'tel'    => 'nullable|string|max:11',
            'email'  => 'nullable|email|max:160',
            'adresse'=> 'nullable|string|max:80',
        ]);

        Client::create($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Commandes du client avec Eloquent
        $commandes = $client->commandes()
            ->with('details')
            ->orderByDesc('date_commande')
            ->get()
            ->map(function ($cmd) {
                $cmd->nb_articles = $cmd->details->sum('quantite');
                return $cmd;
            });

        $totalCommandes = $commandes->count();
        $totalAchats = $commandes->sum('total_ttc');

        // Produits achetés via relations Eloquent
        $produits = Product::select(
                'produits.id',
                'produits.nomp',
                DB::raw('SUM(detail_commande.quantite) as quantite_totale'),
                DB::raw('SUM(detail_commande.quantite * detail_commande.prix_unitaire) as montant_total'),
                DB::raw('ROUND(AVG(detail_commande.prix_unitaire)) as prix_moyen')
            )
            ->join('detail_commande', 'produits.id', '=', 'detail_commande.produit_id')
            ->join('commandes', 'detail_commande.commande_id', '=', 'commandes.id')
            ->where('commandes.client_id', $client->id)
            ->groupBy('produits.id', 'produits.nomp')
            ->orderByDesc('montant_total')
            ->get();

        return view('admin.clients.show', compact(
            'client', 'commandes', 'totalCommandes', 'totalAchats', 'produits'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:50',
            'nomc'   => 'nullable|string|max:50',
            'tel'    => 'nullable|string|max:11',
            'email'  => 'nullable|email|max:160',
            'adresse'=> 'nullable|string|max:80',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        DB::transaction(function () use ($client) {
            // 1. Restaurer le stock pour chaque ligne de commande
            foreach ($client->commandes as $commande) {
                foreach ($commande->details as $detail) {
                    $detail->produit()->increment('quantite', $detail->quantite);
                }
            }

            // 2. Supprimer les détails via les commandes
            foreach ($client->commandes as $commande) {
                $commande->details()->delete();
            }

            // 3. Supprimer les commandes
            $client->commandes()->delete();

            // 4. Supprimer le client
            $client->delete();
        });

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}