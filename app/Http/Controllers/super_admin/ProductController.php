<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $produits = Produit::with('category')
            ->orderByDesc('id')
            ->paginate(10);

        $totalProduits = Produit::count();
        $faibleStock = Produit::where('quantite', '<', 10)->count();
        $valeurStock = Produit::sum(Produit::raw('prix * quantite'));
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

        Produit::create($validated);

        return redirect()->route('admin.super.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit)
    {
        $produit->load('category', 'creator');
        return view('super_admin.products.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        $categories = Category::where('status', 'approved')->orderBy('nomcat')->get();
        return view('super_admin.products.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
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
            if ($produit->image) {
                $oldPath = str_replace('storage/', 'public/', $produit->image);
                Storage::delete($oldPath);
            }
            $path = $request->file('image')->store('produits', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        // Suppression d'image demandée
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($produit->image) {
                $oldPath = str_replace('storage/', 'public/', $produit->image);
                Storage::delete($oldPath);
            }
            $validated['image'] = null;
        }

        $produit->update($validated);

        return redirect()->route('admin.super.produits.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->image) {
            $oldPath = str_replace('storage/', 'public/', $produit->image);
            Storage::delete($oldPath);
        }
        $produit->delete();

        return back()->with('success', 'Produit supprimé.');
    }
}
