<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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

        return view('admin.category', [
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
        if ($request->filled('image_url') && !preg_match('/^https?:\/\//i', $request->input('image_url'))) {
            $request->merge(['image_url' => 'https://' . ltrim($request->input('image_url'), '/ ')]);
        }

        if (!$request->filled('status')) {
            $request->merge(['status' => 'pending']);
        }

        $validated = $request->validate([
            'nomcat' => 'required|string|max:50|unique:categories,nomcat',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ], [
            'nomcat.required' => 'Le nom de la catégorie est obligatoire',
            'nomcat.unique' => 'Cette catégorie existe déjà',
            'nomcat.max' => 'Le nom ne doit pas dépasser 50 caractères',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP',
            'image.max' => 'L\'image ne doit pas dépasser 2MB',
            'image_url.url' => 'L\'URL de l\'image est invalide',
            'image_url.max' => 'L\'URL de l\'image est trop longue',
            'status.in' => 'Le statut est invalide',
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('categories', 'public');
            $validated['image'] = $path;
        } elseif ($imageUrl) {
            $validated['image'] = $imageUrl;
        }

        Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
            ]);
        }

        return redirect()->route('admin.category')
                       ->with('success', 'Catégorie créée avec succès');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        if ($request->filled('image_url') && !preg_match('/^https?:\/\//i', $request->input('image_url'))) {
            $request->merge(['image_url' => 'https://' . ltrim($request->input('image_url'), '/ ')]);
        }

        $validated = $request->validate([
            'nomcat' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories', 'nomcat')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ], [
            'nomcat.required' => 'Le nom de la catégorie est obligatoire',
            'nomcat.unique' => 'Cette catégorie existe déjà',
            'nomcat.max' => 'Le nom ne doit pas dépasser 50 caractères',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP',
            'image.max' => 'L\'image ne doit pas dépasser 2MB',
            'image_url.url' => 'L\'URL de l\'image est invalide',
            'image_url.max' => 'L\'URL de l\'image est trop longue',
            'status.in' => 'Le statut est invalide',
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        if ($request->hasFile('image')) {
            if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }

            $file = $request->file('image');
            $path = $file->store('categories', 'public');
            $validated['image'] = $path;
        } elseif ($imageUrl) {
            if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $imageUrl;
        }

        $category->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès',
            ]);
        }

        return redirect()->route('admin.category')
                       ->with('success', 'Catégorie mise à jour avec succès');
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
