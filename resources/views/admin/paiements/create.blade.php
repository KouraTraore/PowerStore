@extends('layouts.admin')

@section('title', 'Enregistrer un paiement - POWERSTOCK')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="fw-bold">
                    <i class="ti ti-credit-card me-2"></i>
                    Enregistrer un paiement
                </h4>
                <p class="text-secondary">
                    Facture {{ $facture->nomf }} - Client : {{ $facture->commande->client->prenom }} {{ $facture->commande->client->nomc }}
                </p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.paiements.store', $facture->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Montant à payer <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="montant" class="form-control" step="1" min="1" max="{{ $facture->montant_total }}" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                            <div class="small text-secondary mt-1">
                                Montant total de la facture : {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
                            </div>
                            @error('montant') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mode de paiement <span class="text-danger">*</span></label>
                            <select name="mode" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="especes">💰 Espèces</option>
                                <option value="carte">💳 Carte bancaire</option>
                                <option value="mobile_money">📱 Mobile Money (Orange Money, Wave, Moov)</option>
                                <option value="virement">🏦 Virement bancaire</option>
                            </select>
                            @error('mode') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date du paiement <span class="text-danger">*</span></label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
                            @error('date_paiement') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ti ti-info-circle me-1"></i>
                        Une référence unique sera générée automatiquement.
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.factures.show', $facture->id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Enregistrer le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection