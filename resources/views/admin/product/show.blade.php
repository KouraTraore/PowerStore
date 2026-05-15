@extends('layouts.admin')

@section('title', $product->nomp)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Détails du produit</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="img-fluid rounded" style="max-height: 300px;">
                        @else
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="ti ti-package" style="font-size: 64px;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h3>{{ $product->nomp }}</h3>
                        <p class="text-primary fw-bold h4">{{ $product->formatted_price }}</p>
                        <p>{!! $product->stock_badge !!}</p>
                        <hr>
                        <p><strong>Catégorie :</strong> {{ $product->category->nomcat ?? 'Non catégorisé' }}</p>
                        <p><strong>Description :</strong></p>
                        <p>{{ $product->description ?: 'Aucune description' }}</p>
                        <hr>
                        <p><strong>Date de création :</strong> {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p><strong>Dernière modification :</strong> {{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning">
                        <i class="ti ti-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection