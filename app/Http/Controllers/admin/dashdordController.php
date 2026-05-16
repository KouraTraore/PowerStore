<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class dashdordController extends Controller
{
    public function index()
    {
        // ========== STATISTIQUES PRINCIPALES ==========
        $totalClients = Client::count();
        $caTotal = Commande::sum('total_ttc'); // Chiffre d'affaires total

        $totalCommandes = Commande::count();
        $commandesLivrees = Commande::where('statut', 'livree')->count();
        $commandesEnAttente = Commande::where('statut', 'en_attente')->count();
        $commandesAnnulees = Commande::where('statut', 'annulee')->count();

        $totalFactures = Facture::count();
        $facturesPayees = Facture::where('etatf', 1)->count();
        $facturesImpayees = Facture::where('etatf', 0)->count();
        $montantTotalFactures = Facture::sum('montant_total') ?? 0;

        // ========== PRODUITS EN STOCK FAIBLE ==========
        $produitsFaibleStock = Product::where('quantite', '<', 10)->count();

        // ========== DERNIÈRES COMMANDES ==========
        $dernieresCommandes = Commande::with('client')
            ->orderBy('date_commande', 'desc')
            ->limit(5)
            ->get();

        // ========== TOP 5 PRODUITS LES PLUS VENDUS (sans relation) ==========
        $topProduits = Product::select('produits.*', DB::raw('COALESCE(SUM(detail_commande.quantite), 0) as total_vendus'))
            ->leftJoin('detail_commande', 'produits.id', '=', 'detail_commande.produit_id')
            ->groupBy('produits.id')
            ->orderBy('total_vendus', 'desc')
            ->limit(5)
            ->get();

        // ========== PRODUITS EN STOCK FAIBLE (liste) ==========
        $lowStockProducts = Product::where('quantite', '<', 10)
            ->orderBy('quantite', 'asc')
            ->limit(5)
            ->get();

        // ========== ÉVOLUTION MENSUELLE DU CA (12 derniers mois) ==========
        $evolutionCA = Commande::select(
                DB::raw('DATE_FORMAT(date_commande, "%b %Y") as mois'),
                DB::raw('SUM(total_ttc) as total')
            )
            ->where('date_commande', '>=', now()->subMonths(12))
            ->groupBy('mois')
            ->orderByRaw('MIN(date_commande)')
            ->get();

        $moisLabels = $evolutionCA->pluck('mois')->toArray();
        $moisData = $evolutionCA->pluck('total')->toArray();

        // ========== RÉPARTITION CLIENTS (exemple) ==========
        $clientsActifs = $totalClients;
        $clientsInactifs = 0;

        // ========== VENTES VS ACHATS (graphique) ==========
        $ventesMois = Commande::select(
                DB::raw('DATE_FORMAT(date_commande, "%b") as mois_abr'),
                DB::raw('SUM(total_ttc) as total_ventes')
            )
            ->where('date_commande', '>=', now()->subMonths(8))
            ->groupBy('mois_abr')
            ->orderByRaw('MIN(date_commande)')
            ->get();

        $labelsVentes = $ventesMois->pluck('mois_abr')->toArray();
        $ventesData = $ventesMois->pluck('total_ventes')->toArray();
        $achatsData = array_fill(0, count($labelsVentes), 0);

        return view('admin.index', compact(
            'totalClients',
            'caTotal',
            'totalCommandes',
            'commandesLivrees',
            'commandesEnAttente',
            'commandesAnnulees',
            'totalFactures',
            'facturesPayees',
            'facturesImpayees',
            'montantTotalFactures',
            'produitsFaibleStock',
            'dernieresCommandes',
            'topProduits',
            'lowStockProducts',
            'moisLabels',
            'moisData',
            'clientsActifs',
            'clientsInactifs',
            'labelsVentes',
            'ventesData',
            'achatsData'
        ));
    }
}