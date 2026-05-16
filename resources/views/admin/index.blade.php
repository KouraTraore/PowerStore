@extends('layouts.admin')

@section('title', 'Dashboard - POWERSTOCK')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="mb-6">
      <h1 class="fs-3 mb-1">Dashboard</h1>
      <p>Votre tableau de bord POWERSTORE</p>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-3 col-12">
    <div class="card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2">
      <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-primary text-white rounded-2">
          <i class="ti ti-report-analytics fs-4"></i>
        </div>
        <div>
          <h2 class="mb-3 fs-6">Chiffre d'affaires</h2>
<h3 class="fw-bold mb-0" style="white-space: nowrap;">
     {{ number_format($caTotal, 0, ',', ' ') }}  FCFA
</h3>
          <p class="text-primary mb-0 small">Global</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-12">
    <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
      <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-success text-white rounded-2">
          <i class="ti ti-repeat fs-4"></i>
        </div>
        <div>
          <h2 class="mb-3 fs-6">Commandes livrées</h2>
          <h3 class="fw-bold mb-0">{{ $commandesLivrees }}</h3>
          <p class="text-success mb-0 small">sur {{ $totalCommandes }} commandes</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-12">
    <div class="card p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
      <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-info text-white rounded-2">
          <i class="ti ti-currency-dollar fs-4"></i>
        </div>
        <div>
          <h2 class="mb-3 fs-6">Total factures</h2>
          <h3 class="fw-bold mb-0">{{ $totalFactures }}</h3>
          <p class="text-info mb-0 small">{{ $facturesPayees }} payées</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-12">
    <div class="card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2">
      <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-warning text-white rounded-2">
          <i class="ti ti-notes fs-4"></i>
        </div>
        <div>
          <h2 class="mb-3 fs-6">Clients</h2>
          <h3 class="fw-bold mb-0">{{ $totalClients }}</h3>
          <p class="text-warning mb-0 small">fidèles clients</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-4 col-12">
    <div class="card">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
          <div>
            <h3 class="fw-bold h4">{{ number_format($caTotal, 0, ',', ' ') }} FCFA</h3>
            <span>Chiffre d'affaires total</span>
          </div>
          <div><i class="ti ti-layers-subtract fs-1 text-primary"></i></div>
        </div>
        <div class="d-flex justify-content-between align-items-center small">
          <div class="text-muted">Depuis le début</div>
          <div><a href="{{ route('admin.commandes.index') }}" class="link-primary text-decoration-underline">Voir</a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-12">
    <div class="card">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
          <div>
            <h3 class="fw-bold h4">{{ number_format($montantTotalFactures, 0, ',', ' ') }} FCFA</h3>
            <span>Montant total facturé</span>
          </div>
          <div><i class="ti ti-credit-card fs-1 text-danger"></i></div>
        </div>
        <div class="d-flex justify-content-between align-items-center small">
          <div class="text-muted">{{ $facturesPayees }} payées sur {{ $totalFactures }}</div>
          <div><a href="{{ route('admin.factures.index') }}" class="link-primary text-decoration-underline">Voir</a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-12">
    <div class="card">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
          <div>
            <h3 class="fw-bold h4">{{ number_format($commandesEnAttente) }}</h3>
            <span>Commandes en attente</span>
          </div>
          <div><i class="ti ti-cash-banknote fs-1 text-warning"></i></div>
        </div>
        <div class="d-flex justify-content-between align-items-center small">
          <div class="text-muted">À traiter</div>
          <div><a href="{{ route('admin.commandes.index') }}" class="link-primary text-decoration-underline">Voir</a></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-transparent px-4 py-3">
        <h3 class="h5 mb-0">Évolution du chiffre d'affaires</h3>
        <div><span class="badge bg-primary">12 derniers mois</span></div>
      </div>
      <div class="card-body p-4">
        <canvas id="caChart" height="300"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-transparent px-4 py-3">
        <h3 class="h5 mb-0">Aperçu global</h3>
        <div><span class="badge bg-primary">En temps réel</span></div>
      </div>
      <div class="card-body p-4">
        <div class="row text-center">
          <div class="col-6 border-end">
            <div class="text-center">
              <h2 class="mb-1">{{ $totalClients }}</h2>
              <p class="text-success mb-2">Clients</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <h2 class="mb-1">{{ $totalCommandes }}</h2>
              <p class="text-warning mb-2">Commandes</p>
            </div>
          </div>
        </div>
        <div class="row text-center border-top mt-4 pt-4">
          <div class="col-4 border-end">
            <h3 class="fw-bold mb-2">{{ $commandesLivrees }}</h3>
            <small class="text-secondary">Livrées</small>
          </div>
          <div class="col-4 border-end">
            <h3 class="fw-bold mb-2">{{ $commandesEnAttente }}</h3>
            <small class="text-secondary">En attente</small>
          </div>
          <div class="col-4">
            <h3 class="fw-bold mb-2">{{ $commandesAnnulees }}</h3>
            <small class="text-secondary">Annulées</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <h4 class="mb-0 h5">Top produits vendus</h4>
        <button class="btn btn-sm btn-outline-secondary" disabled><i class="ti ti-calendar"></i> Ce mois</button>
      </div>
      <ul class="list-group list-group-flush">
        @forelse($topProduits as $produit)
        <li class="list-group-item d-flex align-items-center gap-3">
          <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="ti ti-package fs-3 text-secondary"></i>
          </div>
          <div class="flex-grow-1">
            <p class="mb-1">{{ $produit->nomp }}</p>
            <div class="d-flex align-items-center gap-2 text-muted">
              <small class="fw-semibold">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</small>
              <small>•</small>
              <small>{{ $produit->total_vendus }} vendu(s)</small>
            </div>
          </div>
          <span class="badge bg-primary-subtle text-primary border border-primary">{{ round(($produit->total_vendus / max(1, $totalCommandes)) * 100) }}%</span>
        </li>
        @empty
        <li class="list-group-item text-center py-4">Aucune vente enregistrée</li>
        @endforelse
      </ul>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <h4 class="mb-0 h5">Stock faible (< 10)</h4>
        <a href="{{ route('admin.product.index') }}" class="small text-primary text-decoration-underline">Voir tout</a>
      </div>
      <ul class="list-group list-group-flush">
        @forelse($lowStockProducts as $produit)
        <li class="list-group-item d-flex align-items-center gap-3">
          <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="ti ti-box fs-3 text-secondary"></i>
          </div>
          <div class="flex-grow-1">
            <p class="mb-1">{{ $produit->nomp }}</p>
            <small>ID: #{{ $produit->id }}</small>
          </div>
          <div class="d-flex flex-column gap-0 align-items-center">
            <span class="fw-semibold text-danger">{{ $produit->quantite }}</span>
            <small class="text-muted">Restant</small>
          </div>
        </li>
        @empty
        <li class="list-group-item text-center py-4">Tous les stocks sont suffisants</li>
        @endforelse
      </ul>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <h4 class="mb-0 h5">Dernières commandes</h4>
        <button class="btn btn-sm btn-outline-secondary" disabled><i class="ti ti-calendar-event"></i> Récentes</button>
      </div>
      <ul class="list-group list-group-flush">
        @forelse($dernieresCommandes as $commande)
        <li class="list-group-item d-flex align-items-center gap-3">
          <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="ti ti-shopping-cart fs-3 text-secondary"></i>
          </div>
          <div class="flex-grow-1">
            <p class="mb-1">Commande #{{ $commande->id }}</p>
            <div class="d-flex align-items-center gap-2 text-muted">
              <small>{{ $commande->client->prenom ?? '' }} {{ $commande->client->nomc ?? '' }}</small>
              <small>•</small>
              <small>{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</small>
            </div>
          </div>
          @if($commande->statut == 'livree')
            <span class="badge bg-success-subtle text-success">Livrée</span>
          @elseif($commande->statut == 'en_attente')
            <span class="badge bg-warning-subtle text-warning">En attente</span>
          @else
            <span class="badge bg-danger-subtle text-danger">Annulée</span>
          @endif
        </li>
        @empty
        <li class="list-group-item text-center py-4">Aucune commande récente</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Graphique du chiffre d'affaires
  const ctx = document.getElementById('caChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($moisLabels) !!},
      datasets: [{
        label: 'Chiffre d\'affaires (FCFA)',
        data: {!! json_encode($moisData) !!},
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13, 110, 253, 0.05)',
        borderWidth: 2,
        fill: true,
        tension: 0.3,
        pointBackgroundColor: '#0d6efd'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { callback: value => value.toLocaleString('fr-FR') + ' FCFA' }
        }
      }
    }
  });
</script>
@endpush