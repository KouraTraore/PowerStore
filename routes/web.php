<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\ErrorsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\ReportController;
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
Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product');
route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');
route::get('/admin/errors', [ErrorsController::class, 'index'])->name('admin.errors');
