@extends('layouts.admin')

@section('title', 'Ajouter une facture - POWERSTORE')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-plus-circle me-2"></i>
                    Ajouter une facture
                </h1>
                <p class="text-secondary">Créer une nouvelle facture</p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.factures.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Client <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select" required>
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->prenom }} {{ $client->nomc }} - {{ $client->tel }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Montant total <span class="text-danger">*</span></label>
                    <input type="number" name="montant_total" class="form-control" step="1000" value="{{ old('montant_total') }}" required>
                    @error('montant_total') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Montant payé</label>
                    <input type="number" name="montant_paye" class="form-control" step="1000" value="{{ old('montant_paye', 0) }}">
                    <small class="text-muted">Laisser 0 pour une facture impayée</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select">
                        <option value="">-- Sélectionner --</option>
                        <option value="Espèces">💰 Espèces</option>
                        <option value="Carte">💳 Carte bancaire</option>
                        <option value="Mobile Money">📱 Mobile Money</option>
                        <option value="Virement">🏦 Virement bancaire</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date de paiement</label>
                    <input type="date" name="date_paiement" class="form-control" value="{{ old('date_paiement') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Référence paiement</label>
                    <input type="text" name="reference_paiement" class="form-control" value="{{ old('reference_paiement') }}" placeholder="Ex: TRX-123456">
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="ti ti-info-circle me-1"></i>
                Le statut de la facture sera automatiquement calculé (Payé / Partiel / Non payé) en fonction du montant payé.
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ti ti-device-floppy me-1"></i> Enregistrer
                </button>
                <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection