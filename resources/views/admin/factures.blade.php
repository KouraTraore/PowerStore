@extends('layouts.admin')

@section('title', 'Factures')

@section('content')

<div class="row mb-4">

    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h1 class="fs-2 fw-bold">Factures</h1>
                <p class="text-secondary">
                    Gestion des factures des clients
                </p>
            </div>

            <button class="btn btn-primary">
                <i class="ti ti-plus"></i>
                Ajouter une facture
            </button>

        </div>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-secondary">
                    Total Factures
                </h6>

                <h2 class="fw-bold">
                    {{ $factures->count() }}
                </h2>

            </div>

        </div>
    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="fw-bold">
                Liste des factures
            </h4>

            <input type="text"
                   class="form-control w-25"
                   placeholder="Rechercher...">

        </div>

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Commande</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>

            </thead>

            <tbody>

                @foreach($factures as $facture)

                <tr>

                    <td>
                        {{ $facture->id }}
                    </td>

                    <td>
                        {{ $facture->client_id }}
                    </td>

                    <td>
                        {{ $facture->commande_id }}
                    </td>

                    <td>
                        {{ $facture->montant_total }} FCFA
                    </td>

                    <td>

                        @if($facture->statut == 'Payé')

                            <span class="badge bg-success">
                                Payé
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Non payé
                            </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection