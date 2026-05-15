@extends('layouts.admin')

@section('title', 'Ajouter un produit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Ajouter un produit</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                        <input type="text" name="nomp" class="form-control @error('nomp') is-invalid @enderror" value="{{ old('nomp') }}" required>
                        @error('nomp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix') }}" min="0" required>
                            @error('prix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" name="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite', 0) }}" min="0" required>
                            @error('quantite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie_id" class="form-select">
                            <option value="">-- Sans catégorie --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nomcat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WEBP (max 2MB)</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection