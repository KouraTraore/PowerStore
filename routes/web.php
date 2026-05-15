<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\CommandeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\PaiementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/admin/dashboard', [dashdordController::class, 'index'])->name('admin.index');

// Clients
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('clients', ClientController::class)->except(['edit', 'update']);
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
});

// Catégories
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');

// Produits
Route::get('/admin/produits', [ProductController::class, 'index'])->name('admin.produits');

// Docs
Route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');

// ========== ROUTES FACTURES (ReportController) ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/factures', [ReportController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [ReportController::class, 'create'])->name('factures.create');
    Route::post('/factures', [ReportController::class, 'store'])->name('factures.store');
    Route::get('/factures/{id}', [ReportController::class, 'show'])->name('factures.show');
    Route::get('/factures/{id}/edit', [ReportController::class, 'edit'])->name('factures.edit');
    Route::put('/factures/{id}', [ReportController::class, 'update'])->name('factures.update');
    Route::get('/factures/{id}/delete', [ReportController::class, 'delete'])->name('factures.delete');
    Route::get('/factures/{id}/pdf', [ReportController::class, 'exportPdf'])->name('factures.pdf');
    Route::get('/factures/export/all', [ReportController::class, 'exportAllPdf'])->name('factures.export.all');
    Route::get('/factures/{id}/email', [ReportController::class, 'sendEmail'])->name('factures.email');
    Route::get('/paiements/dashboard', [ReportController::class, 'paiementsDashboard'])->name('paiements.dashboard');
});

// ========== ROUTES COMMANDES ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('commandes', CommandeController::class)->except(['show']);
    Route::get('commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::get('commandes/{id}/delete', [CommandeController::class, 'delete'])->name('commandes.delete');
    Route::match(['get', 'put'], 'commandes/{id}/statut/{statut}', [CommandeController::class, 'changeStatut'])->name('commandes.changeStatut');
    Route::get('commandes/{id}/choix-facture', [CommandeController::class, 'choixFactureLivraison'])->name('commandes.choix-facture');
    Route::post('commandes/{id}/livrer-avec-facture', [CommandeController::class, 'livrerAvecFacture'])->name('commandes.livrer-avec-facture');
    Route::get('commandes/{id}/print', [CommandeController::class, 'printView'])->name('commandes.print');
});

// ========== ROUTES PAIEMENTS ==========
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/create/{facture_id}', [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/paiements/{facture_id}', [PaiementController::class, 'store'])->name('paiements.store');
});
// Admin routes
Route::prefix('/admin')->group(function () {
    Route::get('/dashboard', [dashdordController::class, 'index'])->name('admin.index');
    
    // Category routes
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('admin.category.show');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
    
    // Product routes
    Route::get('/product', [ProductController::class, 'index'])->name('admin.product');
    
    // Other routes
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/docs', [DocsController::class, 'index'])->name('admin.docs');
    Route::get('/errors', [ErrorsController::class, 'index'])->name('admin.errors');

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

