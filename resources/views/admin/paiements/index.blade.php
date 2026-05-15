@extends('layouts.admin')

@section('title', 'Historique des paiements - POWERSTOCK')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="fs-2 fw-bold">
            <i class="ti ti-history me-2"></i>
            Historique des paiements
        </h1>
        <p class="text-secondary">Liste de tous les paiements enregistrés</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Facture</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $p)
                    <tr>
                        <td><strong>{{ $p->reference }}</strong></td>
                        <td><a href="{{ route('admin.factures.show', $p->facture->id) }}">{{ $p->facture->nomf }}</a></td>
                        <td>{{ $p->facture->commande->client->prenom ?? '' }} {{ $p->facture->commande->client->nomc ?? '' }}</td>
                        <td>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $p->mode_label }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Aucun paiement enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection