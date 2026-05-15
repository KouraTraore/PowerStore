@extends('layouts.admin')

@section('title', 'Modifier le statut - Facture ' . $facture->nomf)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="fw-bold">
                    <i class="ti ti-edit-circle me-2"></i>
                    Modifier le statut de paiement
                </h4>
                <p class="text-secondary">Facture {{ $facture->nomf }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.factures.update', $facture->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Statut actuel :</label>
                        <div class="mb-3">{!! $facture->statut_badge !!}</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nouveau statut</label>
                        <select name="etatf" class="form-select form-select-lg" required>
                            <option value="0" {{ $facture->etatf == 0 ? 'selected' : '' }}>Non payée</option>
                            <option value="1" {{ $facture->etatf == 1 ? 'selected' : '' }}>Payée</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-1"></i>
                        Changer le statut modifie manuellement l'état de la facture.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.factures.show', $facture->id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection