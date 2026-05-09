@extends('layouts.admin')

@section('title', 'Ajouter une facture')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-plus-circle me-2"></i>
                    Ajouter une facture
                </h1>
                <p class="text-secondary">Créer une nouvelle facture avec gestion de paiement</p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg">
    <div class="card-body p-4">
        <form action="{{ route('admin.factures.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-user"></i> Client *
                    </label>
                    <select name="client_id" class="form-select form-select-lg" required>
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">
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
                           placeholder="Ex: 750000" step="1000" required id="montant_total">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-moneybag"></i> Montant payé
                    </label>
                    <input type="number" name="montant_paye" class="form-control form-control-lg" 
                           value="0" step="1000" id="montant_paye">
                    <small class="text-muted">0 = paiement futur | Montant total = payé intégralement</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-credit-card"></i> Mode de paiement
                    </label>
                    <select name="mode_paiement" class="form-select form-select-lg">
                        <option value="">Sélectionner</option>
                        <option value="Espèces">💰 Espèces</option>
                        <option value="Carte">💳 Carte bancaire</option>
                        <option value="Mobile Money">📱 Mobile Money</option>
                        <option value="Virement">🏦 Virement bancaire</option>
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-calendar"></i> Date de paiement
                    </label>
                    <input type="date" name="date_paiement" class="form-control form-control-lg">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">
                        <i class="ti ti-barcode"></i> Référence paiement
                    </label>
                    <input type="text" name="reference_paiement" class="form-control form-control-lg" 
                           placeholder="Ex: TRX-2026-001">
                </div>

                <div class="col-12">
                    <hr>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        Le statut sera automatiquement défini selon le montant payé
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="ti ti-device-floppy"></i> Enregistrer la facture
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
    document.getElementById('montant_paye').addEventListener('input', function() {
        let total = parseFloat(document.getElementById('montant_total').value) || 0;
        let paye = parseFloat(this.value) || 0;
        if(paye > total) {
            this.value = total;
            alert('Le montant payé ne peut pas dépasser le montant total !');
        }
    });
</script>

@endsection