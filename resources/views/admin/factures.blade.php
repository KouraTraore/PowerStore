@extends('layouts.admin')

@section('title', 'Factures')

@section('content')

<style>
    .statut-paye {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .statut-non-paye {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .card-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-action {
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .facture-card {
        transition: all 0.3s ease;
    }

    .facture-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
</style>

<div class="row mb-4">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h1 class="fs-2 fw-bold mb-1">
                    <i class="ti ti-file-invoice me-2"></i>
                    Factures
                </h1>

                <p class="text-secondary mb-0">
                    Gestion des factures des clients
                </p>
            </div>

            <a href="{{ route('admin.factures.create') }}"
               class="btn btn-primary btn-lg shadow-sm">

                <i class="ti ti-plus me-2"></i>
                Nouvelle facture

            </a>

        </div>

    </div>
</div>

<!-- Statistiques -->

<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card card-stats shadow-sm border-0 facture-card">

            <div class="card-body">

                <h6>Total Factures</h6>

                <h2 class="fw-bold">
                    {{ $factures->count() }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-success bg-opacity-10 shadow-sm border-0 facture-card">

            <div class="card-body">

                <h6 class="text-success">
                    Factures Payées
                </h6>

                <h2 class="fw-bold text-success">
                    {{ $factures->where('statut', 'Payé')->count() }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-danger bg-opacity-10 shadow-sm border-0 facture-card">

            <div class="card-body">

                <h6 class="text-danger">
                    Non Payées
                </h6>

                <h2 class="fw-bold text-danger">
                    {{ $factures->where('statut', 'Non payé')->count() }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-info bg-opacity-10 shadow-sm border-0 facture-card">

            <div class="card-body">

                <h6 class="text-info">
                    Montant Total
                </h6>

                <h2 class="fw-bold text-info">

                    {{ number_format($factures->sum('montant_total'), 0, ',', ' ') }}

                    FCFA

                </h2>

            </div>

        </div>

    </div>

</div>

<!-- Tableau -->

<div class="card border-0 shadow-lg facture-card">

    <div class="card-header bg-white border-0 pt-4 px-4">

        <div class="d-flex justify-content-between align-items-center">

            <h4 class="fw-bold mb-0">
                Liste des factures
            </h4>

            <input type="text"
                   class="form-control w-25"
                   placeholder="Rechercher...">

        </div>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="bg-light">

                    <tr>

                        <th>ID</th>
                        <th>Client</th>
                        <th>Commande</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($factures as $facture)

                    <tr>

                        <td>
                            #{{ $facture->id }}
                        </td>

                        <td>

                            {{ $facture->client->nom ?? 'Client '.$facture->client_id }}

                        </td>

                        <td>

                            #CMD-{{ $facture->commande_id }}

                        </td>

                        <td>

                            {{ number_format($facture->montant_total, 0, ',', ' ') }}

                            FCFA

                        </td>

                        <td>

                            @if($facture->statut == 'Payé')

                                <span class="badge statut-paye px-3 py-2 rounded-pill">

                                    Payé

                                </span>

                            @else

                                <span class="badge statut-non-paye px-3 py-2 rounded-pill">

                                    Non payé

                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                <!-- Modifier -->

                                <a href="{{ route('admin.factures.edit', $facture->id) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    <i class="ti ti-edit"></i>

                                </a>

                                <!-- Supprimer -->

                                <a href="{{ route('admin.factures.delete', $facture->id) }}"
                                   class="btn btn-sm btn-outline-danger">

                                    <i class="ti ti-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

                            <h5 class="text-secondary">

                                Aucune facture trouvée

                            </h5>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection