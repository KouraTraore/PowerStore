@extends('layouts.super_admin')

@section('title', $category->nomcat)
@section('page-title', 'Détails de la catégorie')
@section('breadcrumb', 'Super Admin › Catégories › ' . $category->nomcat)

@push('styles')
<style>
    .btn-back {
        width: 34px; height: 34px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-secondary); text-decoration: none;
    }
    .btn-back i { font-size: 17px; }

    .detail-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
    }
    .detail-header {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid var(--border-color);
    }
    .detail-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        background: var(--bg-hover);
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    .detail-img-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--accent);
        flex-shrink: 0;
    }
    .detail-body {
        padding: 24px;
    }
    .detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .detail-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Badges de statut */
    .badge-status-approved { background: #F0FDF4; color: #166534; }
    .badge-status-pending  { background: #FEF3C7; color: #92400E; }
    .badge-status-rejected { background: #FEF2F2; color: #991B1B; }
    .badge-status-default  { background: var(--bg-hover); color: var(--text-muted); }

    /* Table dans la carte */
    .table {
        color: var(--text-primary);
    }
    .table th {
        border-bottom: 1px solid var(--border-light);
        color: var(--text-muted);
    }
    .table td {
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.super.categories.index') }}" class="btn-back">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div>
                <h1 style="font-size:18px;font-weight:700;color:var(--text-primary);margin:0">{{ $category->nomcat }}</h1>
                <p style="font-size:13px;color:var(--text-muted);margin:0">Fiche détaillée</p>
            </div>
        </div>

        <div class="detail-card mb-4">
            <div class="detail-header">
                @if($category->image)
                    <img src="{{ asset($category->image) }}" class="detail-img" alt="{{ $category->nomcat }}">
                @else
                    <div class="detail-img-placeholder">
                        <i class="ti ti-category"></i>
                    </div>
                @endif
                <div>
                    <h3 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0">{{ $category->nomcat }}</h3>
                    <p style="font-size:13px;color:var(--text-muted);margin-top:4px">
                        Statut :
                        <span class="badge-status-{{ $category->status }}"
                              style="padding:2px 10px;border-radius:20px;font-size:12px;font-weight:600">
                            {{ $category->status }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <span class="detail-label">Créée par</span>
                    <span class="detail-value">{{ $category->creator->username ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Approuvée par</span>
                    <span class="detail-value">{{ $category->approver->username ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date d'approbation</span>
                    <span class="detail-value">{{ $category->approved_at ? \Carbon\Carbon::parse($category->approved_at)->format('d/m/Y H:i') : '—' }}</span>
                </div>
                @if($category->rejection_reason)
                <div class="detail-row">
                    <span class="detail-label">Motif de rejet</span>
                    <span class="detail-value" style="color:#991B1B">{{ $category->rejection_reason }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Nombre de produits</span>
                    <span class="detail-value">{{ $category->produits->count() }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="background:var(--bg-card);border-bottom:1px solid var(--border-color);color:var(--text-primary);">
                <span><i class="ti ti-package me-2" style="color:var(--accent)"></i>Produits dans cette catégorie</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $category->produits->count() }} produit(s)</span>
            </div>
            @if($category->produits->isNotEmpty())
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->produits as $produit)
                        <tr>
                            <td style="font-weight:600">{{ $produit->nomp }}</td>
                            <td>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $produit->quantite }}</td>
                            <td>{{ number_format($produit->prix * $produit->quantite, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center py-4" style="color:var(--text-muted)">
                Aucun produit dans cette catégorie.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
