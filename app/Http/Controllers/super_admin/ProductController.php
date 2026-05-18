<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $produits = Product::with('category')
            ->orderByDesc('id')
            ->paginate(10);

        $totalProduits = Product::count();
        $faibleStock = Product::where('quantite', '<', 10)->count();
        $valeurStock = Product::sum(Product::raw('prix * quantite'));
        $categoriesCount = Category::count();

        return view('super_admin.products.index', compact(
            'produits',
            'totalProduits',
            'faibleStock',
            'valeurStock',
            'categoriesCount'
        ));
    }

    public function create()
    {
        $categories = Category::where('status', 'approved')->orderBy('nomcat')->get();
        return view('super_admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomp'          => 'required|string|max:50',
            'prix'          => 'required|numeric|min:0',
            'quantite'      => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie_id'  => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('produits', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $validated['created_by'] = Auth::id();

        Product::create($validated);

        return redirect()->route('admin.super.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'creator');
        return view('super_admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 'approved')->orderBy('nomcat')->get();
        return view('super_admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nomp'          => 'required|string|max:50',
            'prix'          => 'required|numeric|min:0',
            'quantite'      => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie_id'  => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                $oldPath = str_replace('storage/', 'public/', $product->image);
                Storage::delete($oldPath);
            }
            $path = $request->file('image')->store('produits', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        // Suppression d'image demandée
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($product->image) {
                $oldPath = str_replace('storage/', 'public/', $product->image);
                Storage::delete($oldPath);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('admin.super.produits.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $oldPath = str_replace('storage/', 'public/', $product->image);
            Storage::delete($oldPath);
        }
        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }
}
