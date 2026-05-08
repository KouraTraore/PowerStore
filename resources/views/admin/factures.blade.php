@extends('layouts.admin')

@section('title', 'Factures')

@section('content')

<div class="row mb-4">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h1 class="fs-2 fw-bold">Factures</h1>
                <p class="text-secondary">
                    Gestion complète des factures clients
                </p>
            </div>

            <div>
                <button class="btn btn-primary">
                    <i class="ti ti-plus"></i>
                    Ajouter une facture
                </button>
            </div>

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
                    120
                </h2>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h6 class="text-secondary">
                    Factures Payées
                </h6>

                <h2 class="fw-bold text-success">
                    95
                </h2>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h6 class="text-secondary">
                    Non Payées
                </h6>

                <h2 class="fw-bold text-danger">
                    25
                </h2>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h6 class="text-secondary">
                    Revenus
                </h6>

                <h2 class="fw-bold">
                    5 000 000 FCFA
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
                    <th>Produit</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <tr>
                    <td>#001</td>
                    <td>Koura</td>
                    <td>Ordinateur HP</td>
                    <td>07/05/2026</td>
                    <td>500000 FCFA</td>

                    <td>
                        <span class="badge bg-success">
                            Payé
                        </span>
                    </td>

                    <td>

                        <button class="btn btn-sm btn-warning">
                            Modifier
                        </button>

                        <button class="btn btn-sm btn-danger">
                            Supprimer
                        </button>

                    </td>
                </tr>

                <tr>
                    <td>#002</td>
                    <td>Awa</td>
                    <td>Imprimante Canon</td>
                    <td>07/05/2026</td>
                    <td>150000 FCFA</td>

                    <td>
                        <span class="badge bg-danger">
                            Non payé
                        </span>
                    </td>

                    <td>

                        <button class="btn btn-sm btn-warning">
                            Modifier
                        </button>

                        <button class="btn btn-sm btn-danger">
                            Supprimer
                        </button>

                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection