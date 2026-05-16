<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\ErrorsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ClientController;
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

// ========== ROUTES ADMIN (accessibles à tous les connectés) ==========
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [dashdordController::class, 'index'])->name('index');

    // Factures
    Route::get('/factures', [ReportController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [ReportController::class, 'create'])->name('factures.create');
    Route::post('/factures', [ReportController::class, 'store'])->name('factures.store');
    Route::get('/factures/{id}/edit', [ReportController::class, 'edit'])->name('factures.edit');
    Route::put('/factures/{id}', [ReportController::class, 'update'])->name('factures.update');
    Route::get('/factures/{id}/delete', [ReportController::class, 'delete'])->name('factures.delete');
    Route::get('/factures/{id}/pdf', [ReportController::class, 'exportPdf'])->name('factures.pdf');
    Route::get('/factures/export/all', [ReportController::class, 'exportAllPdf'])->name('factures.export.all');
    Route::get('/factures/{id}/email', [ReportController::class, 'sendEmail'])->name('factures.email');
    Route::get('/paiements/dashboard', [ReportController::class, 'paiementsDashboard'])->name('paiements.dashboard');

    // Clients
    Route::resource('clients', ClientController::class)->except(['edit', 'update']);
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
});

// ========== ROUTES PUBLIQUES ADMIN (temporaires) ==========
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product');
Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
Route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');
Route::get('/admin/errors', [ErrorsController::class, 'index'])->name('admin.errors');

// ========== ROUTES SUPER ADMIN (middleware super_admin) ==========
Route::prefix('admin/super')->middleware(['auth', 'super_admin'])->name('admin.super.')->group(function () {
    // Dashboard Super Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
//vue categories
Route::resource('categories', SuperCategoryController::class);
    // Validation des catégories
    Route::get('categories/pending', [SuperCategoryController::class, 'pending'])->name('categories.pending');
    Route::post('categories/{category}/approve', [SuperCategoryController::class, 'approve'])->name('categories.approve');
    Route::post('categories/{category}/reject', [SuperCategoryController::class, 'reject'])->name('categories.reject');
    Route::resource('categories', SuperCategoryController::class)->except(['show']);
// Produits
Route::resource('produits', SuperProductController::class)->except(['show']);
Route::get('produits/{produit}', [SuperProductController::class, 'show'])->name('produits.show');
    });

// ========== AUTHENTIFICATION ==========
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('/admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.index');

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

