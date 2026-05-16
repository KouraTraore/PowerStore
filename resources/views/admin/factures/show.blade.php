@extends('layouts.admin')

@section('title', 'Facture ' . $facture->nomf . ' - POWERSTOCK')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-file-invoice me-2"></i>
                    Facture {{ $facture->nomf }}
                </h1>
                <p class="text-secondary">Détail complet de la facture</p>
            </div>
            <div>
                <!-- Bouton PDF (téléchargement) -->
                <a href="{{ route('admin.factures.pdf', $facture->id) }}" class="btn btn-info me-2" target="_blank">
                    <i class="ti ti-file-pdf me-1"></i> PDF
                </a>
                <!-- Bouton Imprimer (impression directe sans PDF) -->
                <a href="{{ route('admin.factures.print', $facture->id) }}" class="btn btn-secondary me-2" target="_blank">
                    <i class="ti ti-printer me-1"></i> Imprimer
                </a>
                <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="ti ti-info-circle me-2"></i>Informations facture</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Numéro</th>
                        <td><strong>{{ $facture->nomf }}</strong></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($facture->datef)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Statut paiement</th>
                        <td>
                            @if($facture->etatf == 1)
                                <span class="badge bg-success">Payée</span>
                            @else
                                <span class="badge bg-danger">Non payée</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Client</h5>
            </div>
            <div class="card-body">
                @if($facture->client)
                    <p class="mb-1"><strong>{{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? '' }}</strong></p>
                    <p class="mb-1"><i class="ti ti-phone me-1"></i> {{ $facture->client->tel ?? 'Non renseigné' }}</p>
                    <p class="mb-1"><i class="ti ti-mail me-1"></i> {{ $facture->client->email ?? 'Non renseigné' }}</p>
                    <p><i class="ti ti-map-pin me-1"></i> {{ $facture->client->adresse ?? 'Non renseignée' }}</p>
                @else
                    <p class="text-muted">Client non associé.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-package me-2"></i>Détails de la facture</h5>
    </div>
    <div class="card-body">
        <p><strong>Montant total :</strong> {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</p>
        <p><strong>Montant payé :</strong> {{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</p>
        <p><strong>Reste à payer :</strong> {{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA</p>
        @if($facture->mode_paiement)
            <p><strong>Mode de paiement :</strong> {{ $facture->mode_paiement }}</p>
        @endif
        @if($facture->date_paiement)
            <p><strong>Date de paiement :</strong> {{ \Carbon\Carbon::parse($facture->date_paiement)->format('d/m/Y') }}</p>
        @endif
        @if($facture->reference_paiement)
            <p><strong>Référence :</strong> {{ $facture->reference_paiement }}</p>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('admin.factures.edit', $facture->id) }}" class="btn btn-primary">
        <i class="ti ti-edit me-1"></i> Modifier le statut
    </a>
</div>
@endsection