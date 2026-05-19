@extends('layouts.admin')

@section('title', 'Détail commande #' . $commande->id . ' - POWERSTORE')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-file-description me-2"></i>
                    Commande #{{ $commande->id }}
                </h1>
                <p class="text-secondary">Détail complet de la commande</p>
            </div>
            <div>
                <a href="{{ route('admin.commandes.print', $commande->id) }}" class="btn btn-info me-2" target="_blank">
                    <i class="ti ti-printer me-1"></i> Imprimer
                </a>
                <a href="{{ route('admin.commandes.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations commande -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="ti ti-info-circle me-2"></i>Informations générales</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">N° commande</th>
                        <td><strong>#{{ $commande->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td>
                            <strong>{{ $commande->client->prenom ?? '' }} {{ $commande->client->nomc ?? '' }}</strong><br>
                            <small class="text-muted">{{ $commande->client->tel ?? '' }}<br>{{ $commande->client->email ?? '' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Date commande</th>
                        <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td>{!! $commande->statut_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Facture associée</th>
                        <td>
                            @if($commande->facture)
                                <a href="{{ route('admin.factures.show', $commande->facture->id) }}">
                                    {{ $commande->facture->nomf }}
                                </a>
                            @else
                                <span class="text-muted">Aucune facture</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Récapitulatif financier -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="ti ti-report-money me-2"></i>Récapitulatif financier</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Montant total :</span>
                    <strong class="fs-5">{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Statut paiement :</span>
                    @if($commande->facture)
                        @if($commande->facture->etatf == 1)
                            <span class="badge bg-success">Payée</span>
                        @else
                            <span class="badge bg-danger">Non payée</span>
                        @endif
                    @else
                        <span class="badge bg-secondary">Sans facture</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Produits commandés -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-package me-2"></i>Produits commandés</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->details as $detail)
                    <tr>
                        <td><strong>{{ $detail->produit->nomp ?? 'Produit' }}</strong></td>
                        <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $detail->quantite }}</td>
                        <td class="text-end">{{ number_format($detail->prix_unitaire * $detail->quantite, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">TOTAL :</td>
                        <td class="text-end fw-bold fs-5">{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Actions supplémentaires -->
<div class="d-flex justify-content-end gap-2">
    @if($commande->statut == 'en_attente')
        <a href="{{ route('admin.commandes.changeStatut', [$commande->id, 'livree']) }}" class="btn btn-success">
            <i class="ti ti-truck me-1"></i> Marquer comme livrée
        </a>
        <a href="{{ route('admin.commandes.changeStatut', [$commande->id, 'annulee']) }}" class="btn btn-danger" onclick="return confirm('Annuler cette commande ?')">
            <i class="ti ti-ban me-1"></i> Annuler
        </a>
    @endif
    @if($commande->statut == 'livree' && !$commande->facture)
        <a href="{{ route('admin.commandes.choix-facture', $commande->id) }}" class="btn btn-warning">
            <i class="ti ti-file-invoice me-1"></i> Générer une facture
        </a>
    @endif
</div>
@endsection