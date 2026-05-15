@extends('layouts.admin')

@section('title', 'Dashboard des paiements - POWERSTOCK')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-chart-pie me-2"></i>
                    Dashboard des paiements
                </h1>
                <p class="text-secondary">Statistiques et analyse des paiements</p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Retour aux factures
            </a>
        </div>
    </div>
</div>

<!-- Cartes statistiques -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total factures</h6>
                        <h2 class="fw-bold mb-0">{{ $factures->count() }}</h2>
                    </div>
                    <i class="ti ti-file-invoice fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Payées</h6>
                        <h2 class="fw-bold mb-0">{{ $factures->where('etatf', 1)->count() }}</h2>
                    </div>
                    <i class="ti ti-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Impayées</h6>
                        <h2 class="fw-bold mb-0">{{ $factures->where('etatf', 0)->count() }}</h2>
                    </div>
                    <i class="ti ti-x-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Montant total</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($factures->sum('montant_total'), 0, ',', ' ') }} FCFA</h2>
                    </div>
                    <i class="ti ti-chart-bar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques (exemple avec Chart.js) -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Répartition par statut</h5>
            </div>
            <div class="card-body">
                <canvas id="statutChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">Montants par client</h5>
            </div>
            <div class="card-body">
                <canvas id="clientChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique statut
    const ctxStatut = document.getElementById('statutChart').getContext('2d');
    new Chart(ctxStatut, {
        type: 'doughnut',
        data: {
            labels: ['Payées', 'Impayées'],
            datasets: [{
                data: [{{ $factures->where('etatf', 1)->count() }}, {{ $factures->where('etatf', 0)->count() }}],
                backgroundColor: ['#10b981', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Graphique clients (top 5 par montant)
    @php
        $clientsMontants = [];
        foreach($factures as $f) {
            $client = $f->client;
            if($client) {
                $nom = $client->prenom . ' ' . $client->nomc;
                if(!isset($clientsMontants[$nom])) $clientsMontants[$nom] = 0;
                $clientsMontants[$nom] += $f->montant_total;
            }
        }
        arsort($clientsMontants);
        $topClients = array_slice($clientsMontants, 0, 5, true);
    @endphp
    const ctxClient = document.getElementById('clientChart').getContext('2d');
    new Chart(ctxClient, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($topClients)) !!},
            datasets: [{
                label: 'Montant (FCFA)',
                data: {!! json_encode(array_values($topClients)) !!},
                backgroundColor: '#667eea',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection