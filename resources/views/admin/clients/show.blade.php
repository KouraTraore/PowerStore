@extends('layouts.admin')

@section('title', $client->prenom . ' ' . $client->nomc)

@push('styles')
<style>
    .info-card { background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; border-left: 4px solid #E66239; }
    .stat-card { background: white; border-radius: 16px; padding: 20px; text-align: center; border: 1px solid #e2e8f0; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .stat-value { font-size: 32px; font-weight: 700; color: #E66239; }
    .stat-label { color: #64748b; font-size: 13px; margin-top: 5px; }
    .card-custom { background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; }
    .section-title { font-size: 16px; font-weight: 600; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; }
    .badge { font-size: 12px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fs-3 fw-bold mb-1">
                <i class="fa-solid fa-user me-2" style="color: #E66239;"></i>{{ $client->prenom }} {{ $client->nomc }}
            </h1>
            <p class="text-secondary mb-0 small">Fiche client détaillée</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning text-white btn-sm"><i class="fa-solid fa-pen"></i> Modifier</a>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Retour</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $totalCommandes }}</div>
                <div class="stat-label">Commandes</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalAchats, 0, ',', ' ') }} FCFA</div>
                <div class="stat-label">Total achats</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-value">{{ $produits->count() }}</div>
                <div class="stat-label">Produits différents</div>
            </div>
        </div>
    </div>

    <div class="info-card">
        <h5 class="section-title"><i class="fa-solid fa-address-card me-2" style="color: #E66239;"></i>Coordonnées</h5>
        <div class="row">
            <div class="col-md-6 mb-2"><strong>Nom complet :</strong><br>{{ $client->prenom }} {{ $client->nomc }}</div>
            <div class="col-md-6 mb-2"><strong>Téléphone :</strong><br>{{ $client->tel ?? 'Non renseigné' }}</div>
            <div class="col-md-6 mb-2"><strong>Email :</strong><br>{{ $client->email ?? 'Non renseigné' }}</div>
            <div class="col-md-6 mb-2"><strong>Adresse :</strong><br>{{ $client->adresse ?? 'Non renseignée' }}</div>
        </div>
    </div>

    <div class="card-custom">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="fa-solid fa-history me-2" style="color: #E66239;"></i>Commandes</h5>
            <a href="#" class="btn btn-sm btn-success"><i class="fa-solid fa-plus"></i> Nouvelle commande</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Articles</th>
                        <th class="text-end">Total</th>
                        <th>Statut</th>
                        <th>Facture</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $cmd)
                    <tr>
                        <td><strong>#{{ $cmd->id }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($cmd->date_commande)->format('d/m/Y') }}</td>
                        <td>{{ $cmd->nb_articles }} article(s)</td>
                        <td class="text-end fw-bold">{{ number_format($cmd->total_ttc, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($cmd->statut == 'livree')
                                <span class="badge bg-success"><i class="fa-solid fa-check"></i> Livrée</span>
                            @elseif($cmd->statut == 'en_attente')
                                <span class="badge bg-warning"><i class="fa-solid fa-clock"></i> En attente</span>
                            @else
                                <span class="badge bg-danger">Annulée</span>
                            @endif
                        </td>
                        <td>
                            @if($cmd->facture_id)
                                <a href="#" class="btn btn-sm btn-outline-danger"><i class="fa-regular fa-file-pdf"></i></a>
                            @else
                                <span class="text-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune commande</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($produits->isNotEmpty())
    <div class="card-custom">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fa-solid fa-boxes me-2" style="color: #E66239;"></i>Produits achetés</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th class="text-end">Prix moyen</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produits as $p)
                    <tr>
                        <td><strong>{{ $p->nomp }}</strong></td>
                        <td class="text-end">{{ number_format($p->prix_moyen, 0, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ $p->quantite_totale }}</td>
                        <td class="text-end fw-bold text-primary">{{ number_format($p->montant_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">TOTAL GÉNÉRAL :</td>
                        <td class="text-end fw-bold fs-5" style="color: #E66239;">{{ number_format($totalAchats, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <div class="mt-4 d-flex justify-content-end">
        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce client et toutes ses commandes ?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fa-solid fa-trash"></i> Supprimer le client</button>
        </form>
    </div>
</div>
@endsection
