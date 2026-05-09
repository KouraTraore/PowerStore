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
                    <i class="ti ti-receipt"></i>
                    Gestion des factures des clients POWERSTOCK
                </p>
            </div>
            
            <button class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                <i class="ti ti-plus me-2"></i>
                Nouvelle facture
            </button>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stats shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">
                            <i class="ti ti-file-invoice"></i> Total Factures
                        </h6>
                        <h2 class="fw-bold mb-0">
                            {{ $factures->count() }}
                        </h2>
                    </div>
                    <div>
                        <i class="ti ti-receipt fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-success mb-2">
                            <i class="ti ti-check-circle"></i> Payées
                        </h6>
                        <h2 class="fw-bold text-success mb-0">
                            {{ $factures->where('statut', 'Payé')->count() }}
                        </h2>
                    </div>
                    <div>
                        <i class="ti ti-check-circle fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-danger mb-2">
                            <i class="ti ti-x-circle"></i> Non payées
                        </h6>
                        <h2 class="fw-bold text-danger mb-0">
                            {{ $factures->where('statut', 'Non payé')->count() }}
                        </h2>
                    </div>
                    <div>
                        <i class="ti ti-x-circle fs-1 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info bg-opacity-10 shadow-sm border-0 facture-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-info mb-2">
                            <i class="ti ti-currency-cfa"></i> Montant total
                        </h6>
                        <h2 class="fw-bold text-info mb-0">
                            {{ number_format($factures->sum('montant_total'), 0, ',', ' ') }} FCFA
                        </h2>
                    </div>
                    <div>
                        <i class="ti ti-chart-bar fs-1 text-info opacity-50"></i>
                    </div>
                </div>
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
                    <input type="text"
                           id="searchInput"
                           class="form-control border-start-0"
                           placeholder="Rechercher par client, n° facture...">
                </div>
                
                <select class="form-select" style="width: 150px;" id="statutFilter">
                    <option value="">Tous les statuts</option>
                    <option value="Payé">Payé</option>
                    <option value="Non payé">Non payé</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3">
                            <i class="ti ti-hash"></i> N° Facture
                        </th>
                        <th class="border-0 py-3">
                            <i class="ti ti-user"></i> Client
                        </th>
                        <th class="border-0 py-3">
                            <i class="ti ti-package"></i> Commande
                        </th>
                        <th class="border-0 py-3">
                            <i class="ti ti-calendar"></i> Date
                        </th>
                        <th class="border-0 py-3">
                            <i class="ti ti-currency-cfa"></i> Montant
                        </th>
                        <th class="border-0 py-3">
                            <i class="ti ti-status-change"></i> Statut
                        </th>
                        <th class="border-0 pe-4 py-3 text-center">
                            <i class="ti ti-settings"></i> Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="facturesTable">
                    @forelse($factures as $facture)
                    <tr class="facture-row">
                        <td class="ps-4">
                            <strong class="text-primary">#{{ $facture->id }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="ti ti-user text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $facture->client->nom ?? 'Client N°'.$facture->client_id }}</div>
                                    <small class="text-secondary">{{ $facture->client->adresse ?? 'Adresse non renseignée' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                #CMD-{{ $facture->commande_id }}
                            </span>
                        </td>
                        <td>
                            <i class="ti ti-calendar-time me-1"></i>
                            {{ $facture->created_at ? $facture->created_at->format('d/m/Y') : 'Date inconnue' }}
                        </td>
                        <td>
                            <strong class="text-dark">
                                {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
                            </strong>
                        </td>
                        <td>
                            @if($facture->statut == 'Payé')
                                <span class="badge statut-paye px-3 py-2 rounded-pill">
                                    <i class="ti ti-check-circle me-1"></i> Payé
                                </span>
                            @else
                                <span class="badge statut-non-paye px-3 py-2 rounded-pill">
                                    <i class="ti ti-clock me-1"></i> Non payé
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary btn-action" 
                                        onclick="viewInvoice({{ $facture->id }})"
                                        title="Voir la facture">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary btn-action" 
                                        onclick="printInvoice({{ $facture->id }})"
                                        title="Imprimer">
                                    <i class="ti ti-printer"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action" 
                                        onclick="deleteInvoice({{ $facture->id }})"
                                        title="Supprimer">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ti ti-file-invoice fs-1 text-secondary mb-3 d-block"></i>
                            <h5 class="text-secondary">Aucune facture trouvée</h5>
                            <p class="text-secondary mb-0">Commencez par ajouter une nouvelle facture</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-0 py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-secondary">
                Total: {{ $factures->count() }} facture(s)
            </small>
            <button class="btn btn-sm btn-primary" onclick="exportFactures()">
                <i class="ti ti-download"></i> Exporter
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Recherche et filtrage
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('statutFilter').addEventListener('change', function() {
        filterTable();
    });
    
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
            
            if (matchesSearch && matchesStatut) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function viewInvoice(id) {
        window.location.href = '/factures/' + id;
    }
    
    function printInvoice(id) {
        window.open('/factures/' + id + '/print', '_blank');
    }
    
    function deleteInvoice(id) {
        if(confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
            window.location.href = '/factures/' + id + '/delete';
        }
    }
    
    function exportFactures() {
        alert('Export en cours...');
        // Implémentez l'export Excel/PDF ici
    }
</script>
@endsection