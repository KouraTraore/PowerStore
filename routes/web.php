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
Route::get('/admin/factures/create', [ReportController::class, 'create'])->name('admin.factures.create');

Route::post('/admin/factures/store', [ReportController::class, 'store'])->name('admin.factures.store');

Route::get('/admin/factures/edit/{id}', [ReportController::class, 'edit'])->name('admin.factures.edit');

Route::post('/admin/factures/update/{id}', [ReportController::class, 'update'])->name('admin.factures.update');

Route::get('/admin/factures/delete/{id}', [ReportController::class, 'delete'])->name('admin.factures.delete');