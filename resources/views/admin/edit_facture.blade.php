@extends('layouts.admin')

@section('title', 'Modifier la facture')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-edit-circle me-2"></i>
                    Modifier la facture
                </h1>
                <p class="text-secondary">
                    Modifier les informations de la facture #{{ $facture->id }}
                </p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg">
    <div class="card-body p-4">
        <form action="{{ route('admin.factures.update', $facture->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-user"></i> Client *
                    </label>
                    <select name="client_id" class="form-select form-select-lg" required>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $facture->client_id == $client->id ? 'selected' : '' }}>
    {{ $client->prenom }} {{ $client->nomc }} - {{ $client->tel ?? 'Pas de téléphone' }}
</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-currency-cfa"></i> Montant total *
                    </label>
                    <input type="number" name="montant_total" class="form-control form-control-lg" 
                           value="{{ $facture->montant_total }}" step="1000" required id="montant_total">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-moneybag"></i> Montant payé
                    </label>
                    <input type="number" name="montant_paye" class="form-control form-control-lg" 
                           value="{{ $facture->montant_paye }}" step="1000" id="montant_paye">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-credit-card"></i> Mode de paiement
                    </label>
                    <select name="mode_paiement" class="form-select form-select-lg">
                        <option value="">Sélectionner</option>
                        <option value="Espèces" {{ $facture->mode_paiement == 'Espèces' ? 'selected' : '' }}>💰 Espèces</option>
                        <option value="Carte" {{ $facture->mode_paiement == 'Carte' ? 'selected' : '' }}>💳 Carte bancaire</option>
                        <option value="Mobile Money" {{ $facture->mode_paiement == 'Mobile Money' ? 'selected' : '' }}>📱 Mobile Money</option>
                        <option value="Virement" {{ $facture->mode_paiement == 'Virement' ? 'selected' : '' }}>🏦 Virement bancaire</option>
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-calendar"></i> Date de paiement
                    </label>
                    <input type="date" name="date_paiement" class="form-control form-control-lg" 
                           value="{{ $facture->date_paiement ? $facture->date_paiement->format('Y-m-d') : '' }}">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-barcode"></i> Référence paiement
                    </label>
                    <input type="text" name="reference_paiement" class="form-control form-control-lg" 
                           value="{{ $facture->reference_paiement }}" placeholder="Ex: TRX-2026-001">
                </div>

                <div class="col-12">
                    <hr>
                    <h5 class="mb-3">Récapitulatif</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <strong>Total:</strong><br>
                                <span id="preview_total">{{ number_format($facture->montant_total, 0, ',', ' ') }}</span> FCFA
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success">
                                <strong>Payé:</strong><br>
                                <span id="preview_paye">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</span> FCFA
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-danger">
                                <strong>Reste:</strong><br>
                                <span id="preview_reste">{{ number_format($facture->montant_total - $facture->montant_paye, 0, ',', ' ') }}</span> FCFA
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="ti ti-device-floppy"></i> Mettre à jour
                    </button>
                    <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="ti ti-x"></i> Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updatePreview() {
        let total = parseFloat(document.querySelector('[name="montant_total"]').value) || 0;
        let paye = parseFloat(document.querySelector('[name="montant_paye"]').value) || 0;
        if(paye > total) {
            document.querySelector('[name="montant_paye"]').value = total;
            paye = total;
        }
        let reste = total - paye;
        document.getElementById('preview_total').innerText = total.toLocaleString('fr-FR');
        document.getElementById('preview_paye').innerText = paye.toLocaleString('fr-FR');
        document.getElementById('preview_reste').innerText = reste.toLocaleString('fr-FR');
    }
    
    document.querySelector('[name="montant_total"]').addEventListener('input', updatePreview);
    document.querySelector('[name="montant_paye"]').addEventListener('input', updatePreview);
</script>

@endsection