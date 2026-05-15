@extends('layouts.admin')

@section('title', 'Supprimer un produit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">⚠️ Suppression définitive</h4>
            </div>
            <div class="card-body text-center">
                <i class="ti ti-alert-triangle" style="font-size: 64px; color: #dc3545;"></i>
                <h5 class="mt-3">Produit : {{ $product->nomp }}</h5>
                <p class="text-muted">Prix : {{ $product->formatted_price }}</p>
                <p class="text-muted">Stock actuel : {{ $product->quantite }} unités</p>
                
                <div class="alert alert-warning">
                    <strong>Action irréversible !</strong> Cette suppression est définitive.
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <form method="POST" action="{{ route('admin.product.destroy', $product->id) }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label>Tapez <strong class="text-danger">SUPPRIMER</strong> pour confirmer :</label>
                        <input type="text" name="confirm" id="confirmInput" class="form-control text-center" 
                               placeholder="SUPPRIMER" style="font-family: monospace; font-size: 18px;"
                               oninput="this.value = this.value.toUpperCase()">
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" id="deleteBtn" class="btn btn-danger" disabled>
                            <i class="ti ti-trash"></i> Supprimer définitivement
                        </button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const confirmInput = document.getElementById('confirmInput');
const deleteBtn = document.getElementById('deleteBtn');

confirmInput.addEventListener('input', function() {
    if (this.value === 'SUPPRIMER') {
        deleteBtn.disabled = false;
    } else {
        deleteBtn.disabled = true;
    }
});
</script>
@endsection