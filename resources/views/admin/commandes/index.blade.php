@extends('layouts.admin')

@section('title', 'Commandes - POWERSTOCK')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-shopping-cart me-2"></i>
                    Commandes
                </h1>
                <p class="text-secondary">Gestion des commandes clients</p>
            </div>
            <a href="{{ route('admin.commandes.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Nouvelle commande
            </a>
        </div>
    </div>
</div>

<!-- Cartes statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total commandes</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                    </div>
                    <i class="ti ti-package fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Livrées</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['livrees'] }}</h2>
                    </div>
                    <i class="ti ti-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">En attente</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['en_attente'] }}</h2>
                    </div>
                    <i class="ti ti-clock fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Annulées</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['annulees'] }}</h2>
                    </div>
                    <i class="ti ti-x-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des commandes -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
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
                        <td><strong>#{{ $c->id }}</strong></td>
                        <td>
                            <strong>{{ $c->client->prenom ?? '' }} {{ $c->client->nomc ?? '' }}</strong><br>
                            <small class="text-muted">{{ $c->client->tel ?? '' }}</small>
                        </td>
                        <td>{{ $c->produit->nomp ?? 'N/A' }}</strong></td>
                        <td>{{ $c->details->first()->quantite ?? 1 }}</td>
                        <td>{{ number_format($c->total_ttc, 0, ',', ' ') }} FCFA</strong></td>
                        <td>{{ \Carbon\Carbon::parse($c->date_commande)->format('d/m/Y') }}</td>
                        <td>{!! $c->statut_badge !!}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.commandes.show', $c->id) }}" class="btn btn-outline-secondary" title="Voir"><i class="ti ti-eye"></i></a>
                                <a href="{{ route('admin.commandes.edit', $c->id) }}" class="btn btn-outline-primary" title="Modifier"><i class="ti ti-edit"></i></a>

                                @if($c->statut == 'en_attente')
                                    <a href="{{ route('admin.commandes.changeStatut', [$c->id, 'livree']) }}" class="btn btn-outline-success" title="Livrer">
                                        <i class="ti ti-truck"></i>
                                    </a>
                                @endif

                                <a href="{{ route('admin.commandes.delete', $c->id) }}" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette commande ?')"><i class="ti ti-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Aucune commande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection