@extends('layouts.admin')

@section('title', 'Gestion des produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="ti ti-package me-2"></i>
        Liste des produits
    </h2>
    <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i> Nouveau produit
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="ti ti-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="ti ti-alert-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Cartes statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h6>Total produits</h6>
                <h3>{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h6>Quantité totale</h6>
                <h3>{{ number_format($stats['total_quantity']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-warning text-dark">
            <div class="card-body">
                <h6>Valeur stock</h6>
                <h3>{{ number_format($stats['total_value']) }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-danger text-white">
            <div class="card-body">
                <h6>Stock faible</h6>
                <h3>{{ $stats['low_stock'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.product.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nomcat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock" class="form-select">
                    <option value="">Tous stocks</option>
                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stock faible</option>
                    <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Rupture</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Plus récent</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock croissant</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des produits -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 80px;">Image</th>
                    <th style="width: 200px;">Nom</th>
                    <th style="width: 150px;">Prix</th>
                    <th style="width: 120px;">Stock</th>
                    <th style="width: 150px;">Catégorie</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="align-middle text-center">
                    <!-- Image -->
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mx-auto" style="width: 50px; height: 50px;">
                                <i class="ti ti-package"></i>
                            </div>
                        @endif
                    </td>
                    
                    <!-- Nom -->
                    <td class="fw-bold text-start">{{ $product->nomp }}</td>
                    
                    <!-- Prix -->
                    <td class="text-primary fw-bold">{{ $product->formatted_price }}</td>
                    
                    <!-- Stock -->
                    <td>{!! $product->stock_badge !!}</td>
                    
                    <!-- Catégorie -->
                    <td>
                        @if($product->category)
                            <span class="badge bg-light text-dark">{{ $product->category->nomcat }}</span>
                        @else
                            <span class="text-muted">Non catégorisé</span>
                        @endif
                    </td>
                    
                    <!-- Actions -->
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info btn-sm" title="Voir">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="{{ route('admin.product.delete.confirm', $product->id) }}" class="btn btn-danger btn-sm" title="Supprimer">
                                <i class="ti ti-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="ti ti-package-off" style="font-size: 48px;"></i>
                        <p class="mt-2">Aucun produit trouvé</p>
                        <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Ajouter un produit
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $products->links() }}
    </div>
</div>
@endsection