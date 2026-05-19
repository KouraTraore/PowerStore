@extends('layouts.admin')

@section('title', 'Modifier la commande - POWERSTORE')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-2 fw-bold">
                    <i class="ti ti-edit-circle me-2"></i>
                    Modifier la commande #{{ $commande->id }}
                </h1>
                <p class="text-secondary">Modifier les informations de la commande</p>
            </div>
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.commandes.update', $commande->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Client -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Client <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select" required>
                        <option value="">Sélectionner un client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ $commande->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->prenom }} {{ $client->nomc }} - {{ $client->tel }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <!-- Date commande -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date commande <span class="text-danger">*</span></label>
                    <input type="date" name="date_commande" class="form-control" value="{{ \Carbon\Carbon::parse($commande->date_commande)->format('Y-m-d') }}" required>
                    @error('date_commande') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <!-- Statut (AJOUTÉ) -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-3 mb-3 fw-bold">Produits commandés</h5>
            <div id="produits-container">
                @foreach($commande->details as $index => $detail)
                <div class="row produit-row mb-3">
                    <div class="col-md-5">
                        <select name="produits[{{ $index }}][id]" class="form-select produit-select" required>
                            <option value="">Choisir un produit</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" {{ $detail->produit_id == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nomp }} - {{ number_format($produit->prix, 0, ',', ' ') }} FCFA (stock: {{ $produit->quantite }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="produits[{{ $index }}][qty]" class="form-control quantite-input" value="{{ $detail->quantite }}" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control total-ligne bg-light" readonly value="{{ number_format($detail->prix_unitaire * $detail->quantite, 0, ',', ' ') }} FCFA">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-produit">🗑️</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="ajouter-produit" class="btn btn-sm btn-outline-primary mb-3">
                <i class="ti ti-plus me-1"></i> Ajouter un produit
            </button>

            <div class="row mt-4">
                <div class="col-md-4 offset-md-8">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total :</span>
                                <span id="total-general" class="fw-bold fs-5">{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ti ti-device-floppy me-1"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.commandes.index') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    let indexProduit = {{ count($commande->details) }};

    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0}).format(amount) + ' FCFA';
    }

    function recalculerTotal() {
        let totalGeneral = 0;
        document.querySelectorAll('.produit-row').forEach(row => {
            const select = row.querySelector('.produit-select');
            const prix = select.options[select.selectedIndex]?.dataset.prix || 0;
            const qty = row.querySelector('.quantite-input').value || 0;
            const totalLigne = prix * qty;
            row.querySelector('.total-ligne').value = formatMoney(totalLigne);
            totalGeneral += totalLigne;
        });
        document.getElementById('total-general').innerText = formatMoney(totalGeneral);
    }

    function ajouterProduit() {
        const container = document.getElementById('produits-container');
        const newRow = document.createElement('div');
        newRow.className = 'row produit-row mb-3';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="produits[${indexProduit}][id]" class="form-select produit-select" required>
                    <option value="">Choisir un produit</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}">
                            {{ $produit->nomp }} - {{ number_format($produit->prix, 0, ',', ' ') }} FCFA (stock: {{ $produit->quantite }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="produits[${indexProduit}][qty]" class="form-control quantite-input" value="1" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control total-ligne bg-light" readonly value="0 FCFA">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-produit">🗑️</button>
            </div>
        `;
        container.appendChild(newRow);
        indexProduit++;
        attacherEvenements(newRow);
        recalculerTotal();
        toggleRemoveButtons();
    }

    function attacherEvenements(row) {
        row.querySelector('.produit-select').addEventListener('change', recalculerTotal);
        row.querySelector('.quantite-input').addEventListener('input', recalculerTotal);
        row.querySelector('.remove-produit').addEventListener('click', function() {
            if (document.querySelectorAll('.produit-row').length > 1) {
                row.remove();
                recalculerTotal();
                toggleRemoveButtons();
            }
        });
    }

    function toggleRemoveButtons() {
        const rows = document.querySelectorAll('.produit-row');
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-produit');
            if (btn) btn.style.display = rows.length > 1 ? 'block' : 'none';
        });
    }

    document.getElementById('ajouter-produit').addEventListener('click', ajouterProduit);
    document.querySelectorAll('.produit-row').forEach(row => attacherEvenements(row));
    toggleRemoveButtons();
    recalculerTotal();
</script>
@endsection