<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Models\Commande;
use App\Models\Facture;

class DashboardController extends Controller
{
    public function index()
    {
        // ---------- KPI financiers ----------
        $chiffreAffaires = Commande::where('statut', 'livree')->sum('total_ttc');
        $totalCommandes  = Commande::count();
        $facturesImpayees = Facture::where('etatf', 0)
            ->withSum('commandes', 'total_ttc')
            ->get()
            ->sum('commandes_sum_total_ttc');
        $beneficeNet = $chiffreAffaires - $facturesImpayees;

        // ---------- Statistiques secondaires ----------
        $totalUsers      = User::count();
        $usersActifs = User::where('is_active', 1)->count();
        $totalCategories = Category::count();
        $totalProduits   = Product::count();
        $valeurStock     = Product::sum(Product::raw('prix * quantite'));

        // ---------- Ventes mensuelles ----------
        $ventesMensuelles = Commande::where('statut', 'livree')
            ->selectRaw("DATE_FORMAT(date_commande, '%Y-%m') as mois, SUM(total_ttc) as total")
            ->groupBy('mois')
            ->orderBy('mois')
            ->limit(12)
            ->get();

        // ---------- Performance des admins ----------
      $adminsPerformance = User::where('role', 'admin')
    ->withCount(['produits', 'commandes'])
    ->withSum(['commandes as ca_genere' => fn($q) => $q->where('statut', 'livree')], 'total_ttc')
    ->get();

        // ---------- Produits en faible stock ----------
        $produitsFaibleStock = Product::where('quantite', '<', 10)
            ->orderBy('quantite')
            ->limit(5)
            ->get();

        // ---------- Catégories en attente ----------
        $pendingCategoriesCount = Category::where('status', 'pending')->count();
        $pendingCategories = Category::with('creator')
            ->whereIn('status', ['pending', 'rejected'])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('super_admin.dashboard', compact(
            'chiffreAffaires', 'totalCommandes', 'facturesImpayees', 'beneficeNet',
            'totalUsers', 'usersActifs', 'totalCategories', 'totalProduits', 'valeurStock',
            'ventesMensuelles', 'adminsPerformance', 'produitsFaibleStock',
            'pendingCategoriesCount', 'pendingCategories'
        ));
    }
}
