<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    const PER_PAGE = 12;

    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');
        $page = $request->input('page', 1);

        $query = Category::query();

        // Apply filters
        if ($status && $status !== 'all') {
            $query->byStatus($status);
        }

        // Apply search
        if ($search) {
            $query->search($search);
        }

        // Order by latest
        $query->orderByDesc('id');

        // Get total count before pagination
        $total = $query->count();

        // Paginate
        $categories = $query->paginate(self::PER_PAGE, ['*'], 'page', $page);

        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'categories' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'total' => $total,
                    'per_page' => self::PER_PAGE,
                ],
            ]);
        }

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
            'status' => $status,
            'total' => $total,
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
{
    // On garde la correction de l'URL de l'image si elle est saisie sans http
    if ($request->filled('image_url') && !preg_match('/^https?:\/\//i', $request->input('image_url'))) {
        $request->merge(['image_url' => 'https://' . ltrim($request->input('image_url'), '/ ')]);
    }

    // ⚠️ On ne demande PLUS le champ 'status' à l'utilisateur
    $validated = $request->validate([
        'nomcat'      => 'required|string|max:50|unique:categories,nomcat',
        'description' => 'nullable|string|max:500',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'image_url'   => 'nullable|url|max:2048',
    ]);

    // ✅ On force le statut "pending" et on ajoute le créateur
    $validated['status']     = 'pending';
    $validated['created_by'] = Auth::id();

    // Traitement de l'image (inchangé)
    $imageUrl = $validated['image_url'] ?? null;
    unset($validated['image_url']);   // on retire ce champ car il n'existe pas dans la table

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories', 'public');
        $validated['image'] = $path;
    } elseif ($imageUrl) {
        $validated['image'] = $imageUrl;
    }

    $category = Category::create($validated);

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json(['message' => 'Catégorie soumise pour validation.', 'category' => $category]);
    }

    return redirect()->route('admin.category')
        ->with('success', 'Catégorie soumise pour validation.');
}

    /**
     * Update the specified category in storage.
     */
     public function update(Request $request, Category $category)
{
    // ❌ Un Admin normal ne peut pas modifier une catégorie déjà approuvée
    if ($category->status === 'approved' && Auth::user()->role !== 'super_admin') {
        return back()->withErrors(['nomcat' => 'Cette catégorie a déjà été validée et ne peut plus être modifiée.']);
    }

    // Nettoyage image_url (inchangé)
    if ($request->filled('image_url') && !preg_match('/^https?:\/\//i', $request->input('image_url'))) {
        $request->merge(['image_url' => 'https://' . ltrim($request->input('image_url'), '/ ')]);
    }

    $validated = $request->validate([
        'nomcat'      => ['required', 'string', 'max:50', Rule::unique('categories', 'nomcat')->ignore($category->id)],
        'description' => 'nullable|string|max:500',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'image_url'   => 'nullable|url|max:2048',
    ]);

    // ➜ Si la catégorie était rejetée, elle repasse en pending après modification
    if ($category->status === 'rejected') {
        $validated['status'] = 'pending';
        $validated['rejection_reason'] = null;
    }
    // Si elle est pending, elle reste pending ; ne pas toucher au statut

    // Gestion de l’image
    $imageUrl = $validated['image_url'] ?? null;
    unset($validated['image_url']);

    if ($request->hasFile('image')) {
        if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->image);
        }
        $path = $request->file('image')->store('categories', 'public');
        $validated['image'] = $path;
    } elseif ($imageUrl) {
        if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->image);
        }
        $validated['image'] = $imageUrl;
    }

    $category->update($validated);

    $message = 'Catégorie mise à jour.';
    if ($category->fresh()->status === 'pending') {
        $message .= ' Elle est en attente de validation.';
    }

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json(['message' => $message, 'category' => $category->fresh()]);
    }

    return redirect()->route('admin.category')->with('success', $message);
}

    /**
     * Delete the specified category.
     */
    public function destroy(Request $request, Category $category)
    {
        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès',
            ]);
        }

        return redirect()->route('admin.category')
                       ->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * Get a single category for editing
     */
    public function show(Category $category)
    {
        if (request()->ajax()) {
            return response()->json($category);
        }

        return redirect()->route('admin.category');
    }
}