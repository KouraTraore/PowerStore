<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\CommandeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\PaiementController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\super_admin\DashboardController;
use App\Http\Controllers\super_admin\UserController;
use App\Http\Controllers\super_admin\CategoryController as SuperCategoryController;
use App\Http\Controllers\super_admin\ProductController as SuperProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ========== AUTHENTIFICATION ==========
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ROUTES ADMIN ==========
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [dashdordController::class, 'index'])->name('index');

    // Clients
    Route::resource('clients', ClientController::class)->except(['edit', 'update']);
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');

    // Catégories
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Produits
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/{id}/delete', [ProductController::class, 'confirmDelete'])->name('product.delete.confirm');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    // Factures
    Route::get('/factures', [ReportController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [ReportController::class, 'create'])->name('factures.create');
    Route::post('/factures', [ReportController::class, 'store'])->name('factures.store');
    Route::get('/factures/{id}', [ReportController::class, 'show'])->name('factures.show');
    Route::get('/factures/{id}/edit', [ReportController::class, 'edit'])->name('factures.edit');
    Route::put('/factures/{id}', [ReportController::class, 'update'])->name('factures.update');
    Route::get('/factures/{id}/delete', [ReportController::class, 'delete'])->name('factures.delete');
    Route::get('/factures/{id}/pdf', [ReportController::class, 'exportPdf'])->name('factures.pdf');
    Route::get('/factures/{id}/print', [ReportController::class, 'printView'])->name('factures.print');
    Route::get('/factures/export/all', [ReportController::class, 'exportAllPdf'])->name('factures.export.all');
    Route::get('/factures/{id}/email', [ReportController::class, 'sendEmail'])->name('factures.email');
    Route::get('/paiements/dashboard', [ReportController::class, 'paiementsDashboard'])->name('paiements.dashboard');

    // Commandes
    Route::resource('commandes', CommandeController::class)->except(['show']);
    Route::get('commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::get('commandes/{id}/delete', [CommandeController::class, 'delete'])->name('commandes.delete');
    Route::match(['get', 'put'], 'commandes/{id}/statut/{statut}', [CommandeController::class, 'changeStatut'])->name('commandes.changeStatut');
    Route::get('commandes/{id}/choix-facture', [CommandeController::class, 'choixFactureLivraison'])->name('commandes.choix-facture');
    Route::post('commandes/{id}/livrer-avec-facture', [CommandeController::class, 'livrerAvecFacture'])->name('commandes.livrer-avec-facture');
    Route::get('commandes/{id}/print', [CommandeController::class, 'printView'])->name('commandes.print');

    // Paiements
    Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/create/{facture_id}', [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/paiements/{facture_id}', [PaiementController::class, 'store'])->name('paiements.store');

    // Docs
    Route::get('/docs', [DocsController::class, 'index'])->name('docs');
});

// ========== ROUTES SUPER ADMIN ==========
Route::prefix('admin/super')->middleware(['auth', 'super_admin'])->name('admin.super.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    Route::get('categories/pending', [SuperCategoryController::class, 'pending'])->name('categories.pending');
    Route::post('categories/{category}/approve', [SuperCategoryController::class, 'approve'])->name('categories.approve');
    Route::post('categories/{category}/reject', [SuperCategoryController::class, 'reject'])->name('categories.reject');
    Route::resource('categories', SuperCategoryController::class);

    Route::resource('produits', SuperProductController::class)->except(['show']);
    Route::get('produits/{produit}', [SuperProductController::class, 'show'])->name('produits.show');
});
