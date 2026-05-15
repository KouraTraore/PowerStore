<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\ErrorsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes pour l'admin
Route::prefix('admin')->name('admin.')->group(function () {
    
    // ✅ AJOUTE CETTE LIGNE POUR admin.index
    Route::get('/dashboard', [dashdordController::class, 'index'])->name('index');
    
    // OU si tu veux garder les deux noms :
    // Route::get('/dashboard', [dashdordController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', [dashdordController::class, 'index'])->name('index');
    
    // Catégories
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    
    // Rapports, docs, erreurs
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/docs', [DocsController::class, 'index'])->name('docs');
    Route::get('/errors', [ErrorsController::class, 'index'])->name('errors');
    
    // ========== TES ROUTES PRODUITS ==========
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/{id}/delete', [ProductController::class, 'confirmDelete'])->name('product.delete.confirm');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});