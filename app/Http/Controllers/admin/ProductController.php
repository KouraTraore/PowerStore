<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{


    /**
     * Liste des produits avec filtres (INDEX)
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // 🔍 RECHERCHE par nom du produit
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomp', 'like', '%' . $search . '%');
        }

        // 🏷️ FILTRE par catégorie
        if ($request->filled('category') && $request->category != '') {
            $query->where('categorie_id', $request->category);
        }

        // 📦 FILTRE par stock (low = stock faible, out = rupture)
        if ($request->filled('stock')) {
            if ($request->stock == 'low') {
                $query->where('quantite', '>', 0)->where('quantite', '<', 5);
            } elseif ($request->stock == 'out') {
                $query->where('quantite', '<=', 0);
            }
        }

        // 🔄 TRI
        $sort = $request->get('sort', 'id_desc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('nomp', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nomp', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('quantite', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('quantite', 'desc');
                break;
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        // 📄 Pagination (avec conservation des paramètres de filtre)
        $products = $query->paginate(7)->withQueryString();

        // 📊 Statistiques pour le dashboard
        $stats = [
            'total' => Product::count(),
            'total_quantity' => Product::sum('quantite'),
            'total_value' => Product::sum(DB::raw('prix * quantite')),
            'low_stock' => Product::where('quantite', '>', 0)->where('quantite', '<', 5)->count(),
            'out_stock' => Product::where('quantite', '<=', 0)->count(),
        ];

        // 🏷️ Récupérer les catégories pour le filtre
        $categories =  Category::where('status', 'approved')->orderBy('nomcat')->get();

        return view('admin.product.index', compact('products', 'stats', 'categories'));
    }

    /**
     * Formulaire d'ajout (CREATE)
     */
    public function create()
    {
        $categories = Category::where('status', 'approved')->orderBy('nomcat')->get();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Enregistrement d'un produit (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomp' => 'required|string|max:255',
            'prix' => 'required|integer|min:0',
            'quantite' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = [
            'nomp' => $request->nomp,
            'prix' => $request->prix,
            'quantite' => $request->quantite,
            'description' => $request->description,
            'categorie_id' => $request->categorie_id,
            'created_by' => Auth::id(),
        ];

        // 🖼️ Gestion de l'image
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $data['image'] = 'storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('admin.product.index')
            ->with('success', 'Produit ajouté avec succès');
    }

    /**
     * Voir un produit (SHOW)
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.product.show', compact('product'));
    }

    /**
     * Formulaire de modification (EDIT)
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('nomcat')->get();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Mise à jour d'un produit (UPDATE)
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nomp' => 'required|string|max:255',
            'prix' => 'required|integer|min:0',
            'quantite' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = [
            'nomp' => $request->nomp,
            'prix' => $request->prix,
            'quantite' => $request->quantite,
            'description' => $request->description,
            'categorie_id' => $request->categorie_id,
        ];

        // 🖼️ Gestion de la nouvelle image
        if ($request->hasFile('product_image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                $oldPath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('product_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $data['image'] = 'storage/' . $path;
        }

        // 🗑️ Suppression de l'image demandée
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($product->image) {
                $oldPath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $data['image'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.product.index')
            ->with('success', 'Produit modifié avec succès');
    }

    /**
     * Confirmation de suppression (DELETE CONFIRM)
     */
    public function confirmDelete($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.delete', compact('product'));
    }

    /**
     * Suppression définitive d'un produit (DESTROY)
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // ✅ Vérification de confirmation (SUPPRIMER)
        if ($request->input('confirm') !== 'SUPPRIMER') {
            return redirect()->route('admin.product.delete.confirm', $product->id)
                ->with('error', 'Veuillez saisir "SUPPRIMER" pour confirmer la suppression');
        }

        // 🖼️ Supprimer l'image associée
        if ($product->image) {
            $oldPath = str_replace('storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Produit supprimé avec succès');
    }
}
