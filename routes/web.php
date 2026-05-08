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
Route::get('/admin/dashboard', [dashdordController::class, 'index'])->name('admin.index');
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/produits', [ProduitsController::class, 'index'])->name('admin.produits');
route::get('/admin/factures', [ReportController::class, 'index'])->name('admin.factures');
route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');
route::get('/admin/commandes', [ErrorsController::class, 'index'])->name('admin.commandes');