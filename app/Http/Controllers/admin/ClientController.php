<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
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

    // Liste paginée avec jointure pour stats
    $clients = Client::select(
        'clients.id',
        'clients.prenom',
        'clients.nomc',
        'clients.tel',
        'clients.email',
        'clients.adresse'
    )
    ->selectRaw('COUNT(commandes.id) as nb_commandes')
    ->selectRaw('COALESCE(SUM(commandes.total_ttc), 0) as total_achats')
    ->leftJoin('commandes', 'clients.id', '=', 'commandes.client_id')
    ->when($search, fn($q) => $q->search($search))
    ->groupBy(
        'clients.id',
        'clients.prenom',
        'clients.nomc',
        'clients.tel',
        'clients.email',
        'clients.adresse'
    )
    ->orderBy('clients.prenom', $order)
    ->paginate(10);

    // Statistiques sans utiliser de relations
    $totalClients = Client::count();
    $clientsActifs = DB::table('commandes')->distinct()->count('client_id');
    $caTotal = DB::table('commandes')->sum('total_ttc');

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

        // Commandes du client
        $commandes = DB::table('commandes')
            ->where('client_id', $client->id)
            ->orderByDesc('date_commande')
            ->get()
            ->map(function ($cmd) {
                $cmd->nb_articles = DB::table('detail_commande')
                    ->where('commande_id', $cmd->id)
                    ->count();
                return $cmd;
            });

        $totalCommandes = $commandes->count();
        $totalAchats = $commandes->sum('total_ttc');

        // Produits achetés (regroupés)
        $produits = DB::table('detail_commande')
            ->join('commandes', 'detail_commande.commande_id', '=', 'commandes.id')
            ->join('produits', 'detail_commande.produit_id', '=', 'produits.id')
            ->where('commandes.client_id', $client->id)
            ->select(
                'produits.id',
                'produits.nomp',
                DB::raw('SUM(detail_commande.quantite) as quantite_totale'),
                DB::raw('SUM(detail_commande.quantite * detail_commande.prix_unitaire) as montant_total'),
                DB::raw('ROUND(AVG(detail_commande.prix_unitaire)) as prix_moyen')
            )
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
            $details = DB::table('detail_commande')
                ->join('commandes', 'detail_commande.commande_id', '=', 'commandes.id')
                ->where('commandes.client_id', $client->id)
                ->select('detail_commande.produit_id', 'detail_commande.quantite')
                ->get();

            foreach ($details as $detail) {
                DB::table('produits')
                    ->where('id', $detail->produit_id)
                    ->increment('quantite', $detail->quantite);
            }

            // 2. Supprimer d'abord les détails
            $commandeIds = DB::table('commandes')
                ->where('client_id', $client->id)
                ->pluck('id');

            DB::table('detail_commande')
                ->whereIn('commande_id', $commandeIds)
                ->delete();

            // 3. Supprimer les commandes
            DB::table('commandes')
                ->where('client_id', $client->id)
                ->delete();

            // 4. Supprimer le client
            $client->delete();
        });

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}

