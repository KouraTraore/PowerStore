@extends('layouts.admin')

@section('title', 'Factures')

@section('content')

<style>
    .statut-paye { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .statut-partiel { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .statut-non-paye { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    .card-stats { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; transition: all 0.2s ease; }
    .btn-action { transition: all 0.2s ease; }
    .btn-action:hover { transform: translateY(-2px); }
    .facture-card { transition: all 0.3s ease; }
    .facture-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
    .progress-bar-custom { height: 8px; border-radius: 10px; }
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
                    <i class="ti ti-receipt"></i>
                    Gestion des factures et des paiements
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.paiements.dashboard') }}" class="btn btn-info btn-lg shadow-sm">
                    <i class="ti ti-chart-pie me-2"></i>
                    Dashboard Paiements
                </a>
                <a href="{{ route('admin.factures.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="ti ti-plus me-2"></i>
                    Nouvelle facture
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques avancées -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stats shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total Factures</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                    </div>
                    <i class="ti ti-receipt fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-success mb-2">Payées</h6>
                        <h2 class="fw-bold text-success mb-0">{{ $stats['paye'] }}</h2>
                    </div>
                    <i class="ti ti-check-circle fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-warning mb-2">Paiement Partiel</h6>
                        <h2 class="fw-bold text-warning mb-0">{{ $stats['partiel'] }}</h2>
                    </div>
                    <i class="ti ti-clock fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-danger mb-2">Non payées</h6>
                        <h2 class="fw-bold text-danger mb-0">{{ $stats['non_paye'] }}</h2>
                    </div>
                    <i class="ti ti-x-circle fs-1 text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deuxième ligne de stats financières -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-info bg-opacity-10 shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-info mb-2">Montant Total</h6>
                <h3 class="fw-bold text-info mb-0">{{ number_format($stats['montant_total'], 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success bg-opacity-10 shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-success mb-2">Total Payé</h6>
                <h3 class="fw-bold text-success mb-0">{{ number_format($stats['montant_paye'], 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger bg-opacity-10 shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-danger mb-2">Reste à Payer</h6>
                <h3 class="fw-bold text-danger mb-0">{{ number_format($stats['reste_total'], 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des factures -->
<div class="card border-0 shadow-lg facture-card">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="fw-bold mb-0">
                <i class="ti ti-list-details me-2"></i>
                Liste des factures
            </h4>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Rechercher...">
                </div>
                <select class="form-select" style="width: 150px;" id="statutFilter">
                    <option value="">Tous</option>
                    <option value="Payé">Payé</option>
                    <option value="Partiel">Partiel</option>
                    <option value="Non payé">Non payé</option>
                </select>
                <a href="{{ route('admin.factures.export.all') }}" class="btn btn-success">
                    📑 Exporter tout
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3">N° Facture</th>
                        <th class="border-0 py-3">Client</th>
                        <th class="border-0 py-3">Date</th>
                        <th class="border-0 py-3">Montant Total</th>
                        <th class="border-0 py-3">Paiement</th>
                        <th class="border-0 py-3">Statut</th>
                        <th class="border-0 py-3">Mode Paiement</th>
                        <th class="border-0 pe-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="facturesTable">
                    @forelse($factures as $facture)
                    <tr class="facture-row">
                        <td class="ps-4">
                            <strong class="text-primary">#{{ $facture->id }}</strong>
                            <small class="d-block text-muted">{{ $facture->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            @if($facture->client)
                               <strong>{{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? 'Client' }}</strong>
                                <small class="d-block text-muted">{{ $facture->client->tel ?? '' }}</small>
                            @else
                                Client #{{ $facture->client_id }}
                            @endif
                        </td>
                        <td>
                            <i class="ti ti-calendar-time me-1"></i>
                            {{ $facture->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <strong class="text-dark">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong>
                        </td>
                        <td style="min-width: 150px;">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</span>
                                <span class="text-muted">/ {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="progress progress-bar-custom">
                                <div class="progress-bar bg-success" style="width: {{ $facture->pourcentage_paye }}%"></div>
                            </div>
                            <small class="text-muted">{{ $facture->pourcentage_paye }}% payé</small>
                        </td>
                        <td>
                            @if($facture->statut == 'Payé')
                                <span class="badge statut-paye px-3 py-2 rounded-pill">
                                    ✅ Payé
                                </span>
                            @elseif($facture->statut == 'Partiel')
                                <span class="badge statut-partiel px-3 py-2 rounded-pill">
                                    ⏳ Partiel
                                </span>
                            @else
                                <span class="badge statut-non-paye px-3 py-2 rounded-pill">
                                    ❌ Non payé
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($facture->mode_paiement)
                                <span class="badge bg-secondary">
                                    @if($facture->mode_paiement == 'Espèces') 💰
                                    @elseif($facture->mode_paiement == 'Carte') 💳
                                    @elseif($facture->mode_paiement == 'Mobile Money') 📱
                                    @elseif($facture->mode_paiement == 'Virement') 🏦
                                    @endif
                                    {{ $facture->mode_paiement }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            <div class="btn-group" role="group">
                                <!-- Modifier -->
                                <a href="{{ route('admin.factures.edit', $facture->id) }}" 
                                   class="btn btn-sm btn-outline-primary btn-action" 
                                   title="Modifier">
                                    ✏️
                                </a>
                                <!-- PDF -->
                                <a href="{{ route('admin.factures.pdf', $facture->id) }}" 
                                   class="btn btn-sm btn-outline-danger btn-action" 
                                   title="Télécharger PDF">
                                    📄
                                </a>
                                <!-- Email -->
                                <a href="{{ route('admin.factures.email', $facture->id) }}" 
                                   class="btn btn-sm btn-outline-success btn-action" 
                                   title="Envoyer par email">
                                    📧
                                </a>
                                <!-- Supprimer -->
                                <a href="{{ route('admin.factures.delete', $facture->id) }}" 
                                   class="btn btn-sm btn-outline-danger btn-action" 
                                   title="Supprimer"
                                   onclick="return confirm('Supprimer cette facture ?')">
                                    🗑️
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            📭 Aucune facture trouvée
                            <h5 class="text-secondary">Commencez par ajouter une nouvelle facture</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('statutFilter').addEventListener('change', filterTable);

    function filterTable() {
        const searchText = document.getElementById('searchInput').value.toLowerCase();
        const statutFilter = document.getElementById('statutFilter').value;
        const rows = document.querySelectorAll('.facture-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statutCell = row.cells[5];
            const statut = statutCell.textContent.trim();
            
            let matchesSearch = text.includes(searchText);
            let matchesStatut = !statutFilter || statut.includes(statutFilter);
            
            row.style.display = (matchesSearch && matchesStatut) ? '' : 'none';
        });
    }
</script>

@endsection