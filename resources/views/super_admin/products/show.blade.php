@extends('layouts.super_admin')

@section('title', $produit->nomp)
@section('page-title', 'Détails du produit')
@section('breadcrumb', 'Super Admin › Produits › ' . $produit->nomp)

@push('styles')
<style>
    .detail-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
    }
    .detail-header {
        display: flex; align-items: center; gap: 20px;
        padding: 24px; border-bottom: 1px solid var(--border-color);
    }
    .detail-img {
        width: 80px; height: 80px;
        border-radius: 12px; object-fit: cover;
        background: var(--bg-hover); border: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    .detail-img-placeholder {
        width: 80px; height: 80px;
        border-radius: 12px; background: var(--accent-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: var(--accent); flex-shrink: 0;
    }
    .detail-body { padding: 24px; }
    .detail-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    .detail-label { font-size: 13px; color: var(--text-muted); font-weight: 500; }
    .detail-value { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .btn-back { width: 34px; height: 34px; border: 1px solid var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; }
    .btn-back i { font-size: 17px; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.super.produits.index') }}" class="btn-back"><i class="ti ti-arrow-left"></i></a>
            <div>
                <h1 style="font-size:18px;font-weight:700;color:var(--text-primary);margin:0">{{ $produit->nomp }}</h1>
                <p style="font-size:13px;color:var(--text-muted);margin:0">Fiche détaillée</p>
            </div>
        </div>

        <div class="detail-card mb-4">
            <div class="detail-header">
                @if($produit->image)
                    <img src="{{ asset($produit->image) }}" class="detail-img" alt="{{ $produit->nomp }}">
                @else
                    <div class="detail-img-placeholder"><i class="ti ti-package"></i></div>
                @endif
                <div>
                    <h3 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0">{{ $produit->nomp }}</h3>
                    @if($produit->category)
                        <span class="badge" style="background:#EFF6FF;color:#1D4ED8;margin-top:4px">{{ $produit->category->nomcat }}</span>
                    @endif
                </div>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <span class="detail-label">Prix</span>
                    <span class="detail-value">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quantité en stock</span>
                    <span class="detail-value">{{ $produit->quantite }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valeur totale</span>
                    <span class="detail-value">{{ number_format($produit->prix * $produit->quantite, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Catégorie</span>
                    <span class="detail-value">{{ $produit->category->nomcat ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Créé par</span>
                    <span class="detail-value">{{ $produit->creator->username ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Description</span>
                    <span class="detail-value" style="max-width:300px;text-align:right">{{ $produit->description ?: 'Aucune' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
