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