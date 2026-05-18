<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Liste toutes les catégories.
     */
    public function index()
    {
        $categories = Category::with(['creator', 'approver'])
            ->orderByDesc('id')
            ->paginate(10);

        $pendingCount = Category::where('status', 'pending')->count();

        return view('super_admin.categories.index', compact('categories', 'pendingCount'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('super_admin.categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomcat' => 'required|string|max:50',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Par défaut, une catégorie créée par le Super Admin est approuvée directement
        $validated['status'] = 'approved';
        $validated['created_by'] = Auth::id();
        $validated['approved_by'] = Auth::id();
        $validated['approved_at'] = now();

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        Category::create($validated);

        return redirect()
            ->route('admin.super.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Category $category)
    {
        return view('super_admin.categories.edit', compact('category'));
    }

    /**
     * Mise à jour d'une catégorie.
     */
    public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'nomcat' => 'required|string|max:50',
        'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Suppression de l'image existante
    if ($request->has('delete_image') && $request->delete_image == '1') {
        if ($category->image) {
            $oldPath = str_replace('storage/', 'public/', $category->image);
            Storage::delete($oldPath);
        }
        $category->update(['image' => null]);
    }

    // Upload nouvelle image
    if ($request->hasFile('image')) {
        if ($category->image) {
            $oldPath = str_replace('storage/', 'public/', $category->image);
            Storage::delete($oldPath);
        }
        $path = $request->file('image')->store('categories', 'public');
        $validated['image'] = 'storage/' . $path;
    }

    $category->update($validated);

    return redirect()->route('admin.super.categories.index')
        ->with('success', 'Catégorie modifiée.');
}

    /**
     * vision d'une catégorie.
     */
public function show(Category $category)
{
    $category->load(['creator', 'approver', 'produits']);
    return view('super_admin.categories.show', compact('category'));
}
    /**
     * Suppression d'une catégorie.
     */
    public function destroy(Category $category)
    {
        // Supprimer l'image si elle existe
        if ($category->image) {
            $oldPath = str_replace('storage/', 'public/', $category->image);
            Storage::delete($oldPath);
        }

        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    /**
     * Catégories en attente d'approbation.
     */
    public function pending()
    {
        $categories = Category::with('creator')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->paginate(10);

        $pendingCount = Category::where('status', 'pending')->count();

        return view('super_admin.categories.pending', compact('categories', 'pendingCount'));
    }

    /**
     * Approuver une catégorie.
     */
    public function approve(Category $category)
    {
        $category->update([
            'status'       => 'approved',
            'approved_by'  => Auth::id(),
            'approved_at'  => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Catégorie approuvée.');
    }

    /**
     * Rejeter une catégorie.
     */
    public function reject(Request $request, Category $category)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $category->update([
            'status'           => 'rejected',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', 'Catégorie rejetée.');
    }
}
