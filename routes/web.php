<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\dashdordController;
use App\Http\Controllers\admin\DocsController;
use App\Http\Controllers\admin\ErrorsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/dashboard', [dashdordController::class, 'index'])->name('admin.index');
Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product');
route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
route::get('/admin/docs', [DocsController::class, 'index'])->name('admin.docs');
route::get('/admin/errors', [ErrorsController::class, 'index'])->name('admin.errors');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('clients', ClientController::class)->except(['edit', 'update']);
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
});

Route::get('/test-client-view', function () {
    return view('admin.clients.index');
});
