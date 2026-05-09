@extends('layouts.admin')

@section('title', 'Ajouter une facture')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">Ajouter une facture</h1>
                <p class="text-secondary">Créer une nouvelle facture</p>
            </div>
            <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.factures.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Client ID</label>
                    <input type="number" name="client_id" class="form-control" required>
                    <small class="text-muted">L'ID du client dans la base de données</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Commande ID</label>
                    <input type="number" name="commande_id" class="form-control">
                    <small class="text-muted">Optionnel - Lier à une commande existante</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Montant total</label>
                    <input type="number" name="montant_total" class="form-control" step="1000" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Non payé">Non payé</option>
                        <option value="Payé">Payé</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i>
                        Enregistrer la facture
                    </button>
                    <a href="{{ route('admin.factures.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection