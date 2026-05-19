@extends('layouts.admin')

@section('title', 'Choix de la facture - POWERSTORE')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="fw-bold text-center">
                    <i class="ti ti-truck me-2"></i>
                    Livraison de la commande #{{ $commande->id }}
                </h4>
                <p class="text-center text-secondary">
                    Client : <strong>{{ $commande->client->prenom }} {{ $commande->client->nomc }}</strong><br>
                    Montant total : <strong>{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</strong>
                </p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.commandes.livrer-avec-facture', $commande->id) }}">
                    @csrf

                    @if($facturesExistantes->count() > 0)
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ajouter à une facture existante (non payée)</label>
                        <select name="facture_id" class="form-select mb-3">
                            <option value="">-- Sélectionnez une facture --</option>
                            @foreach($facturesExistantes as $facture)
                                <option value="{{ $facture->id }}">
                                    {{ $facture->nomf }} - Montant: {{ number_format($facture->commande->total_ttc ?? 0, 0, ',', ' ') }} FCFA
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" name="option" value="existing" class="btn btn-outline-primary w-100">
                            <i class="ti ti-plus-circle me-1"></i> Ajouter à cette facture
                        </button>
                    </div>
                    <hr>
                    @endif

                    <div class="d-grid gap-3">
                        <button type="submit" name="option" value="new" class="btn btn-success btn-lg">
                            <i class="ti ti-file-invoice me-2"></i> Créer une nouvelle facture
                        </button>
                        <button type="submit" name="option" value="none" class="btn btn-secondary btn-lg">
                            <i class="ti ti-truck me-2"></i> Livrer sans facture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection