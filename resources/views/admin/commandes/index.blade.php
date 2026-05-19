@extends('layouts.admin')

@section('title', 'Commandes - POWERSTORE')

@section('content')
<style>
    /* Couleurs spécifiques aux commandes (orange) */
    .btn-commande { background: #f59e0b; border-color: #f59e0b; color: white; }
    .btn-commande:hover { background: #d97706; border-color: #d97706; }
    .badge-commande-livree { background: #d1fae5; color: #065f46; }
    .badge-commande-attente { background: #fed7aa; color: #92400e; }
    .badge-commande-annulee { background: #fee2e2; color: #991b1b; }
    
    /* Cartes avec couleurs douces */
    .stat-card-total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .stat-card-livree { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .stat-card-attente { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .stat-card-annulee { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    
    .table th { font-weight: 600; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .table td { vertical-align: middle; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fs-2 fw-bold">
            <i class="ti ti-shopping-cart me-2" style="color: #f59e0b;"></i>
            Commandes
        </h1>
        <p class="text-secondary">Gestion des commandes clients</p>
    </div>
    <a href="{{ route('admin.commandes.create') }}" class="btn btn-commande">
        <i class="ti ti-plus me-1"></i> Nouvelle commande
    </a>
</div>

<!-- Cartes statistiques avec couleurs -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card-total shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total commandes</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <i class="ti ti-package fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-livree shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Livrées</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['livrees'] }}</h3>
                    </div>
                    <i class="ti ti-check-circle fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-attente shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">En attente</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['en_attente'] }}</h3>
                    </div>
                    <i class="ti ti-clock fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-annulee shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Annulées</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['annulees'] }}</h3>
                    </div>
                    <i class="ti ti-x-circle fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des commandes -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $c)
                <tr>
                    <td class="fw-bold">#{{ $c->id }}</td>
                    <td>
                        <strong>{{ $c->client->prenom ?? '' }} {{ $c->client->nomc ?? '' }}</strong><br>
                        <small class="text-muted">{{ $c->client->tel ?? '' }}</small>
                    </td>
                    <td>{{ $c->produit->nomp ?? 'N/A' }}</td>
                    <td>{{ $c->details->first()->quantite ?? 1 }}</td>
                    <td class="fw-bold">{{ number_format($c->total_ttc, 0, ',', ' ') }} FCFA</strong></td>
                    <td>{{ \Carbon\Carbon::parse($c->date_commande)->format('d/m/Y') }}</td>
                    <td>
                        @if($c->statut == 'livree')
                            <span class="badge badge-commande-livree"><i class="ti ti-check-circle me-1"></i> Livrée</span>
                        @elseif($c->statut == 'en_attente')
                            <span class="badge badge-commande-attente"><i class="ti ti-clock me-1"></i> En attente</span>
                        @else
                            <span class="badge badge-commande-annulee"><i class="ti ti-x-circle me-1"></i> Annulée</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.commandes.show', $c->id) }}" class="btn btn-outline-secondary" title="Voir"><i class="ti ti-eye"></i></a>
                            <a href="{{ route('admin.commandes.edit', $c->id) }}" class="btn btn-outline-warning" title="Modifier"><i class="ti ti-edit"></i></a>
                            @if($c->statut == 'en_attente')
                                <a href="{{ route('admin.commandes.changeStatut', [$c->id, 'livree']) }}" class="btn btn-outline-success" title="Livrer"><i class="ti ti-truck"></i></a>
                            @endif
                            <a href="{{ route('admin.commandes.delete', $c->id) }}" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette commande ?')"><i class="ti ti-trash"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ti ti-shopping-cart-off fs-1 text-secondary mb-3 d-block"></i>
                        <p>Aucune commande trouvée</p>
                        <a href="{{ route('admin.commandes.create') }}" class="btn btn-commande btn-sm">Créer une commande</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <small class="text-muted">
            Affichage de {{ $commandes->firstItem() ?? 0 }} à {{ $commandes->lastItem() ?? 0 }}
            sur {{ $commandes->total() }} commande(s)
        </small>
        <div>
            {{ $commandes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection