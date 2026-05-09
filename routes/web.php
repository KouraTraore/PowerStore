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
Route::get('/admin/dashboard', [dashdordController::class, 'index'])->name('admin.index');
Route::get('/admin/clients', [ClientController::class, 'index'])->name('admin.clients');
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/produits', [ProductController::class, 'index'])->name('admin.produits');
route::get('/admin/factures', [ReportController::class, 'index'])->name('admin.factures');
route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');
route::get('/admin/commandes', [ErrorsController::class, 'index'])->name('admin.commandes');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/factures', [App\Http\Controllers\admin\ReportController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [App\Http\Controllers\admin\ReportController::class, 'create'])->name('factures.create');
    Route::post('/factures', [App\Http\Controllers\admin\ReportController::class, 'store'])->name('factures.store');
    Route::get('/factures/{id}/edit', [App\Http\Controllers\admin\ReportController::class, 'edit'])->name('factures.edit');
    Route::put('/factures/{id}', [App\Http\Controllers\admin\ReportController::class, 'update'])->name('factures.update');
    Route::get('/factures/{id}/delete', [App\Http\Controllers\admin\ReportController::class, 'delete'])->name('factures.delete');
    Route::delete('/factures/{id}', [App\Http\Controllers\admin\ReportController::class, 'destroy'])->name('factures.destroy');
});