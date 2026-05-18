@extends('layouts.super_admin')

@section('title', 'Nouveau produit')
@section('page-title', 'Nouveau produit')
@section('breadcrumb', 'Super Admin › Produits › Créer')

@push('styles')
<style>
    .btn-back { width: 34px; height: 34px; border: 1px solid var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; }
    .btn-back i { font-size: 17px; }
    .btn-cancel { border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); padding: 8px 18px; border-radius: 8px; font-size: 13px; text-decoration: none; }
    .btn-cancel:hover { background: var(--bg-hover); }
    .btn-save { padding: 8px 18px; border-radius: 8px; font-size: 13px; }

    .card { background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); }
    .card-header { background: var(--bg-card); border-bottom: 1px solid var(--border-color); }
    .form-label { color: var(--text-primary); }
    .form-control, .form-select { background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary); }
    .form-control:focus, .form-select:focus { background: var(--bg-card); border-color: var(--accent); color: var(--text-primary); }
    .input-group-text { background: var(--bg-hover); border: 1px solid var(--border-color); color: var(--text-muted); }
    .invalid-feedback { color: #ef4444; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.super.produits.index') }}" class="btn-back"><i class="ti ti-arrow-left"></i></a>
            <div>
                <h1 style="font-size:18px;font-weight:700;color:var(--text-primary);margin:0">Nouveau produit</h1>
                <p style="font-size:13px;color:var(--text-muted);margin:0">Ajouter un produit au catalogue</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span><i class="ti ti-package me-2" style="color:var(--accent)"></i>Informations</span></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.super.produits.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom du produit <span style="color:#ef4444">*</span></label>
                        <input type="text" name="nomp" class="form-control @error('nomp') is-invalid @enderror" value="{{ old('nomp') }}" required>
                        @error('nomp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Prix (FCFA) <span style="color:#ef4444">*</span></label>
                            <input type="number" name="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix') }}" min="0" required>
                            @error('prix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Quantité <span style="color:#ef4444">*</span></label>
                            <input type="number" name="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}" min="0" required>
                            @error('quantite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catégorie <span style="color:#ef4444">*</span></label>
                        <select name="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror" required>
                            <option value="">Sélectionner...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nomcat }}</option>
                            @endforeach
                        </select>
                        @error('categorie_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Description optionnelle...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Image <span style="font-size:11px;font-weight:400;color:var(--text-muted)">(optionnel)</span></label>
                        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" onchange="previewImage(this)">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="img-preview" style="display:none;margin-top:10px">
                            <img id="preview-img" style="width:80px;height:80px;object-fit:cover;border-radius:10px;border:1px solid var(--border-color)">
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.super.produits.index') }}" class="btn-cancel">Annuler</a>
                        <button type="submit" class="btn btn-accent btn-save"><i class="ti ti-check me-1"></i> Créer le produit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('img-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
