@extends('layouts.admin')

@section('title', 'Factures - POWERSTOCK')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-file-invoice me-2"></i>
                    Factures
                </h1>
                <p class="text-secondary">Gestion des factures clients</p>
            </div>
            <a href="{{ route('admin.factures.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Nouvelle facture
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>N° Facture</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td><strong>{{ $facture->nomf ?? 'N/A' }}</strong></td>
                        <td>
                            @if($facture->client)
                                {{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? '' }}<br>
                                <small>{{ $facture->client->tel ?? '' }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                        <td>{{ \Carbon\Carbon::parse($facture->datef)->format('d/m/Y') }}</td>
                        <td>
                            @if($facture->etatf == 1)
                                <span class="badge bg-success">Payée</span>
                            @else
                                <span class="badge bg-danger">Non payée</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.factures.show', $facture->id) }}" class="btn btn-outline-secondary"><i class="ti ti-eye"></i></a>
                                <a href="{{ route('admin.factures.edit', $facture->id) }}" class="btn btn-outline-primary"><i class="ti ti-edit"></i></a>
                                <a href="{{ route('admin.factures.pdf', $facture->id) }}" class="btn btn-outline-info" target="_blank"><i class="ti ti-file-pdf"></i></a>
                                <a href="{{ route('admin.factures.delete', $facture->id) }}" class="btn btn-outline-danger" onclick="return confirm('Supprimer cette facture ?')"><i class="ti ti-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Aucune facture trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection