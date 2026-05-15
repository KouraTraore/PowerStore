@extends('layouts.admin')

@section('title', 'Modifier client')

@push('styles')
<style>
    /* même style que create */
    .form-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 30px; max-width: 700px; margin: 0 auto; }
    .form-label { font-weight: 600; margin-bottom: 8px; }
    .form-control { border-radius: 10px; padding: 10px 14px; border: 1px solid #e2e8f0; }
    .form-control:focus { border-color: #E66239; box-shadow: 0 0 0 3px rgba(230,98,57,0.1); }
    .btn-primary-custom { background: #E66239; border: none; padding: 10px 24px; border-radius: 12px; color: white; }
    .btn-outline-custom { background: white; border: 1.5px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; color: #64748b; text-decoration: none; }
    .btn-outline-custom:hover { border-color: #E66239; color: #E66239; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fs-3 fw-bold mb-1"><i class="fa-solid fa-pen me-2" style="color: #E66239;"></i>Modifier client</h1>
            <p class="text-secondary mb-0 small">Modifiez les informations de {{ $client->prenom }} {{ $client->nomc }}</p>
        </div>
        <a href="{{ route('admin.clients.show', $client) }}" class="btn-outline-custom"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $client->prenom) }}" required>
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nomc" class="form-control @error('nomc') is-invalid @enderror" value="{{ old('nomc', $client->nomc) }}">
                    @error('nomc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="tel" class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel', $client->tel) }}">
                    @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control @error('adresse') is-invalid @enderror" rows="2">{{ old('adresse', $client->adresse) }}</textarea>
                    @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.clients.show', $client) }}" class="btn-outline-custom">Annuler</a>
                    <button type="submit" class="btn-primary-custom"><i class="fa-solid fa-save"></i> Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
