@extends('layouts.admin')

@section('title', 'Dashboard des Paiements')

@section('content')

<style>
    .statut-paye { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .statut-partiel { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .statut-non-paye { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    .card-stats { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .dashboard-card { transition: all 0.3s ease; }
    .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-2 fw-bold mb-1">
                    <i class="ti ti-chart-pie me-2"></i>
                    Dashboard des Paiements
                </h1>
                <p class="text-secondary mb-0">
                    <i class="ti ti-report-money"></i>
                    Statistiques et analyse des paiements
                </p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                <i class="ti ti-arrow-left me-2"></i>
                Retour aux factures
            </a>
        </div>
    </div>
</div>

<!-- Cartes statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stats shadow-sm border-0 dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">
                            <i class="ti ti-file-invoice"></i> Total Factures
                        </h6>
                        <h2 class="fw-bold mb-0">{{ $factures->count() }}</h2>
                    </div>
                    <i class="ti ti-receipt fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10 shadow-sm border-0 dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-success mb-2">
                            <i class="ti ti-check-circle"></i> Total Payé
                        </h6>
                        <h2 class="fw-bold text-success mb-0">{{ number_format($factures->sum('montant_paye'), 0, ',', ' ') }} FCFA</h2>
                    </div>
                    <i class="ti ti-moneybag fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10 shadow-sm border-0 dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-danger mb-2">
                            <i class="ti ti-x-circle"></i> Reste à Payer
                        </h6>
                        <h2 class="fw-bold text-danger mb-0">{{ number_format($factures->sum('reste_a_payer'), 0, ',', ' ') }} FCFA</h2>
                    </div>
                    <i class="ti ti-alert-circle fs-1 text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning bg-opacity-10 shadow-sm border-0 dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-warning mb-2">
                            <i class="ti ti-chart-bar"></i> Taux de recouvrement
                        </h6>
                        <h2 class="fw-bold text-warning mb-0">
                            @php
                                $total = $factures->sum('montant_total');
                                $paye = $factures->sum('montant_paye');
                                $taux = $total > 0 ? round(($paye / $total) * 100) : 0;
                            @endphp
                            {{ $taux }}%
                        </h2>
                    </div>
                    <i class="ti ti-percent fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row g-3 mb-4">
    <!-- Paiements par mode -->
    <div class="col-md-6">
        <div class="card border-0 shadow-lg dashboard-card h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="fw-bold mb-0">
                    <i class="ti ti-credit-card me-2"></i>
                    Paiements par mode
                </h4>
            </div>
            <div class="card-body">
                <canvas id="paiementsModeChart" style="max-height: 300px;"></canvas>
                <div class="mt-3">
                    @foreach($paiementsParMode as $mode => $montant)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>
                            @if($mode == 'Espèces') 💰
                            @elseif($mode == 'Carte') 💳
                            @elseif($mode == 'Mobile Money') 📱
                            @elseif($mode == 'Virement') 🏦
                            @endif
                            {{ $mode }}
                        </span>
                        <strong>{{ number_format($montant, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ $total > 0 ? ($montant / $total) * 100 : 0 }}%"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Statut des paiements -->
    <div class="col-md-6">
        <div class="card border-0 shadow-lg dashboard-card h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="fw-bold mb-0">
                    <i class="ti ti-status-change me-2"></i>
                    Statut des factures
                </h4>
            </div>
            <div class="card-body">
                <canvas id="statutChart" style="max-height: 300px;"></canvas>
                <div class="mt-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <span class="badge statut-paye px-3 py-2">Payé</span>
                                <h3 class="mt-2 mb-0">{{ $factures->where('statut', 'Payé')->count() }}</h3>
                                <small class="text-muted">factures</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <span class="badge statut-partiel px-3 py-2">Partiel</span>
                                <h3 class="mt-2 mb-0">{{ $factures->where('statut', 'Partiel')->count() }}</h3>
                                <small class="text-muted">factures</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <span class="badge statut-non-paye px-3 py-2">Non payé</span>
                                <h3 class="mt-2 mb-0">{{ $factures->where('statut', 'Non payé')->count() }}</h3>
                                <small class="text-muted">factures</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des factures avec paiements en attente -->
<div class="card border-0 shadow-lg dashboard-card">
    <div class="card-header bg-white border-0 pt-4">
        <h4 class="fw-bold mb-0">
            <i class="ti ti-alert-circle me-2"></i>
            Factures avec paiement en attente ou partiel
        </h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3">N° Facture</th>
                        <th class="border-0 py-3">Client</th>
                        <th class="border-0 py-3">Montant Total</th>
                        <th class="border-0 py-3">Payé</th>
                        <th class="border-0 py-3">Reste</th>
                        <th class="border-0 py-3">Progression</th>
                        <th class="border-0 pe-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $facturesNonPayees = $factures->filter(function($f) {
                            return $f->statut != 'Payé';
                        });
                    @endphp
                    
                    @forelse($facturesNonPayees as $facture)
                    <tr>
                        <td class="ps-4">
                            <strong class="text-primary">#{{ $facture->id }}</strong>
                            <small class="d-block text-muted">{{ $facture->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <strong>{{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? 'Client' }}</strong>
                            <small class="d-block text-muted">{{ $facture->client->tel ?? '' }}</small>
                        </td>
                        <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                        <td>{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <strong class="text-danger">{{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA</strong>
                        </td>
                        <td>
                            <div class="progress" style="height: 8px; width: 150px;">
                                <div class="progress-bar bg-success" style="width: {{ $facture->pourcentage_paye }}%"></div>
                            </div>
                            <small>{{ $facture->pourcentage_paye }}% payé</small>
                        </td>
                        <td class="pe-4 text-center">
                            <a href="{{ route('admin.factures.edit', $facture->id) }}" 
                               class="btn btn-sm btn-outline-primary"
                               title="Enregistrer un paiement">
                                <i class="ti ti-moneybag"></i> Payer
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ti ti-check-circle fs-1 text-success mb-3 d-block"></i>
                            <h5 class="text-success">Toutes les factures sont payées !</h5>
                            <p class="text-secondary mb-0">Félicitations, aucun impayé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des paiements par mode
    const modeCtx = document.getElementById('paiementsModeChart').getContext('2d');
    new Chart(modeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($paiementsParMode)) !!},
            datasets: [{
                data: {!! json_encode(array_values($paiementsParMode)) !!},
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Graphique des statuts
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    new Chart(statutCtx, {
        type: 'bar',
        data: {
            labels: ['Payé', 'Partiel', 'Non payé'],
            datasets: [{
                label: 'Nombre de factures',
                data: [
                    {{ $factures->where('statut', 'Payé')->count() }},
                    {{ $factures->where('statut', 'Partiel')->count() }},
                    {{ $factures->where('statut', 'Non payé')->count() }}
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

@endsection