<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\ErrorsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/admin/dashboard', [dashdordController::class, 'index'])->name('admin.index');

// Clients
Route::get('/admin/clients', [ClientController::class, 'index'])->name('admin.clients');

// Categories
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');

// Produits
Route::get('/admin/produits', [ProductController::class, 'index'])->name('admin.produits');

// Docs
Route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');

// Commandes
Route::get('/admin/commandes', [ErrorsController::class, 'index'])->name('admin.commandes');

// ========== ROUTES FACTURES ==========
Route::prefix('admin')->name('admin.')->group(function () {
    // Gestion des factures
    Route::get('/factures', [ReportController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [ReportController::class, 'create'])->name('factures.create');
    Route::post('/factures', [ReportController::class, 'store'])->name('factures.store');
    Route::get('/factures/{id}/edit', [ReportController::class, 'edit'])->name('factures.edit');
    Route::put('/factures/{id}', [ReportController::class, 'update'])->name('factures.update');
    Route::get('/factures/{id}/delete', [ReportController::class, 'delete'])->name('factures.delete');
    
    // Export PDF
    Route::get('/factures/{id}/pdf', [ReportController::class, 'exportPdf'])->name('factures.pdf');
    Route::get('/factures/export/all', [ReportController::class, 'exportAllPdf'])->name('factures.export.all');
    
    // Email
    Route::get('/factures/{id}/email', [ReportController::class, 'sendEmail'])->name('factures.email');
    
    // Dashboard paiements
    Route::get('/paiements/dashboard', [ReportController::class, 'paiementsDashboard'])->name('paiements.dashboard');
});