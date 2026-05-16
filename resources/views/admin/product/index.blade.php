@extends('layouts.admin')

@section('title', 'Produits - POWERSTOCK')

@section('content')
<style>
    /* Couleurs spécifiques aux produits */
    .badge-prod-low { background: #fee2e2; color: #991b1b; }
    .badge-prod-medium { background: #fed7aa; color: #92400e; }
    .badge-prod-high { background: #d1fae5; color: #065f46; }
    .btn-prod { background: #10b981; border-color: #10b981; color: white; }
    .btn-prod:hover { background: #059669; border-color: #059669; }

    /* Cartes avec dégradés personnalisés pour produits */
    .stat-card-total-prod { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; }
    .stat-card-value-prod { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .stat-card-low-prod { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); color: white; }
    .stat-card-cat-prod { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }

    .table th { font-weight: 600; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .table td { vertical-align: middle; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="ti ti-package me-2" style="color: #10b981;"></i>
        Liste des produits
    </h2>
    <a href="{{ route('admin.product.create') }}" class="btn btn-prod">
        <i class="ti ti-plus"></i> Nouveau produit
    </a>
</div>

<!-- Messages flash -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        <i class="ti ti-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
        <i class="ti ti-alert-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Cartes statistiques colorées (spécifiques produits) -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card-total-prod shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total produits</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                    <i class="ti ti-package fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-value-prod shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Valeur stock</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_value'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    </div>
                    <i class="ti ti-chart-bar fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-low-prod shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Stock faible</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['low_stock'] ?? 0 }}</h3>
                    </div>
                    <i class="ti ti-alert-circle fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-cat-prod shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Catégories</h6>
                        <h3 class="fw-bold mb-0">{{ $categories->count() }}</h3>
                    </div>
                    <i class="ti ti-category fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.product.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->nomcat }}</option>
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
                <button type="submit" class="btn btn-prod w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des produits -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Catégorie</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="text-center">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" width="45" height="45" class="rounded" style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 45px; height: 45px;">
                                <i class="ti ti-package text-secondary"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $product->nomp }}</td>
                    <td class="text-prod-primary fw-bold">{{ number_format($product->prix, 0, ',', ' ') }} FCFA</strong></td>
                    <td>
                        @php
                            $stock = $product->quantite;
                            $badgeClass = $stock <= 0 ? 'badge-prod-low' : ($stock < 10 ? 'badge-prod-medium' : 'badge-prod-high');
                            $label = $stock <= 0 ? 'Rupture' : ($stock < 10 ? 'Stock faible' : 'Stock OK');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $label }} ({{ $stock }})</span>
                    </td>
                    <td>
                        @if($product->category)
                            <span class="badge bg-light text-dark">{{ $product->category->nomcat }}</span>
                        @else
                            <span class="text-muted">Sans catégorie</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-outline-secondary" title="Voir">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-outline-warning" title="Modifier">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="{{ route('admin.product.delete.confirm', $product->id) }}" class="btn btn-outline-danger" title="Supprimer">
                                <i class="ti ti-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="ti ti-package-off fs-1 text-secondary mb-3 d-block"></i>
                        <p>Aucun produit trouvé</p>
                        <a href="{{ route('admin.product.create') }}" class="btn btn-prod btn-sm">Ajouter un produit</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection