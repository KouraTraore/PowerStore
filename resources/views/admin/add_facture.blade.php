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
                    <select name="mode_paiement" id="mode_paiement" class="form-select form-select-lg">
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
                    <input type="text" name="reference_paiement" id="reference_paiement" class="form-control form-control-lg" 
                           readonly style="background-color: #f5f5f5;">
                    <small class="text-muted" id="referenceHint">
                        🔄 Généré automatiquement
                    </small>
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
    // Générer une référence automatique
    function genererReference() {
        const mode = document.getElementById('mode_paiement').value;
        const date = new Date();
        const annee = date.getFullYear();
        const mois = String(date.getMonth() + 1).padStart(2, '0');
        const jour = String(date.getDate()).padStart(2, '0');
        const heure = String(date.getHours()).padStart(2, '0');
        const minute = String(date.getMinutes()).padStart(2, '0');
        const seconde = String(date.getSeconds()).padStart(2, '0');
        
        let reference = '';
        let hint = '';
        
        if (mode === 'Espèces') {
            reference = `ESP-${annee}${mois}${jour}-${heure}${minute}${seconde}`;
            hint = '💰 Paiement en espèces';
        } else if (mode === 'Carte') {
            reference = `CB-${annee}${mois}${jour}-${Math.floor(Math.random() * 10000)}`;
            hint = '💳 Paiement par carte bancaire';
        } else if (mode === 'Mobile Money') {
            reference = `MM-${annee}${mois}${jour}-${Math.floor(Math.random() * 100000)}`;
            hint = '📱 Paiement par Mobile Money';
        } else if (mode === 'Virement') {
            reference = `VIR-${annee}${mois}${jour}-${Math.floor(Math.random() * 10000)}`;
            hint = '🏦 Paiement par virement';
        } else {
            reference = `FACT-${annee}${mois}${jour}-${Math.floor(Math.random() * 1000)}`;
            hint = '🔄 Référence générée automatiquement';
        }
        
        document.getElementById('reference_paiement').value = reference;
        document.getElementById('referenceHint').innerHTML = hint;
    }
    
    // Empêcher le montant payé de dépasser le montant total
    function verifierMontantPaye() {
        let total = parseFloat(document.getElementById('montant_total').value) || 0;
        let paye = parseFloat(document.getElementById('montant_paye').value) || 0;
        
        if (paye > total) {
            document.getElementById('montant_paye').value = total;
            alert('Le montant payé ne peut pas dépasser le montant total !');
        }
    }
    
    // Écouter les événements
    document.getElementById('mode_paiement').addEventListener('change', genererReference);
    document.getElementById('montant_paye').addEventListener('input', verifierMontantPaye);
    document.getElementById('montant_total').addEventListener('input', verifierMontantPaye);
    
    // Générer une référence au chargement de la page
    genererReference();
</script>

@endsection