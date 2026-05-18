@extends('layouts.super_admin')

@section('title', 'Produits')
@section('page-title', 'Gestion des produits')
@section('breadcrumb', 'Super Admin › Produits')

@push('styles')
<style>
    .stat-box {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 18px;
        transition: all 0.2s ease;
    }
    .stat-box:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }
    .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--color, var(--text-primary)); line-height: 1.2; }

    .filter-btn {
        border: 1px solid var(--border-color); background: var(--bg-card);
        border-radius: 8px; padding: 6px 14px; font-size: 13px;
        font-weight: 500; color: var(--text-secondary); transition: all 0.2s; cursor: pointer;
    }
    .filter-btn.active, .filter-btn:hover {
        background: var(--accent-light); border-color: var(--accent); color: var(--accent);
    }

    .table-container {
        border: 1px solid var(--border-color); border-radius: 12px;
        overflow: hidden; background: var(--bg-card);
    }

    .product-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .product-table thead th {
        background: var(--bg-hover); padding: 12px 16px; font-size: 12px;
        font-weight: 600; text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-secondary); border-bottom: 1px solid var(--border-color);
        cursor: pointer; user-select: none; transition: background 0.15s;
    }
    .product-table thead th:hover { background: var(--border-light); }
    .product-table tbody td {
        padding: 14px 16px; border-bottom: 1px solid var(--border-light);
        vertical-align: middle; color: var(--text-primary);
    }
    .product-table tbody tr:hover td { background: var(--bg-hover); }
    .product-table tbody tr:last-child td { border-bottom: none; }

    .product-img-box {
        width: 40px; height: 40px; border-radius: 8px;
        background: var(--bg-hover); border: 1px solid var(--border-color);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; flex-shrink: 0;
    }
    .product-img-box img { width: 100%; height: 100%; object-fit: cover; }
    .product-img-box i { color: var(--text-muted); font-size: 20px; }

    .action-btn {
        width: 32px; height: 32px; border: 1px solid var(--border-color);
        border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;
        color: var(--text-secondary); background: var(--bg-card);
        transition: all 0.2s; cursor: pointer; text-decoration: none; font-size: 14px;
    }
    .action-btn:hover { background: var(--bg-hover); }
    .action-btn.view:hover { color: var(--accent); border-color: var(--accent); }
    .action-btn.edit:hover { color: #D97706; border-color: #D97706; }
    .action-btn.delete:hover { color: #EF4444; border-color: #FECACA; background: var(--bg-hover); }

    .pagination-wrapper { padding: 14px 20px; border-top: 1px solid var(--border-color); }
    .results-counter { font-size: 12px; color: var(--text-muted); }

    .btn-accent {
        background: var(--accent); border: none; color: #fff;
        padding: 8px 16px; border-radius: 8px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: background 0.2s;
    }
    .btn-accent:hover { background: var(--accent-dark); color: #fff; }
    .stock-low { color: #EF4444; font-weight: 600; }
    .stock-ok { color: #166534; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:2px">Produits</h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0">Gérez tous les produits du magasin.</p>
    </div>
    <a href="{{ route('admin.super.produits.create') }}" class="btn-accent">
        <i class="ti ti-plus" style="font-size:15px"></i> Nouveau produit
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="stat-box">
            <div class="stat-label">Total</div>
            <div class="stat-value" style="--color:#1a1a2e;">{{ $totalProduits }}</div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-box">
            <div class="stat-label">Faible stock</div>
            <div class="stat-value" style="--color:#EF4444;">{{ $faibleStock }}</div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-box">
            <div class="stat-label">Valeur stock</div>
            <div class="stat-value" style="--color:#166534; font-size:18px;">{{ number_format($valeurStock, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-box">
            <div class="stat-label">Catégories</div>
            <div class="stat-value" style="--color:#1D4ED8;">{{ $categoriesCount }}</div>
        </div>
    </div>
</div>

{{-- Filtres et Recherche --}}
<div class="d-flex flex-wrap align-items-center gap-3 mb-3">
    <div class="input-group" style="max-width: 300px;">
        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color);"><i class="ti ti-search"></i></span>
        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Rechercher..." style="background: var(--bg-card); color: var(--text-primary); border-color: var(--border-color);">
    </div>
    <div class="btn-group" id="filterButtons">
        <button class="filter-btn active" data-stock="all">Tous</button>
        <button class="filter-btn" data-stock="low">Stock faible (<10)</button>
        <button class="filter-btn" data-stock="ok">En stock</button>
    </div>
    <span class="results-counter ms-auto" id="visibleCounter"></span>
</div>

{{-- Tableau --}}
<div class="table-container">
    <table class="product-table" id="productTable">
        <thead>
            <tr>
                <th data-sort="id">#</th>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th style="width:120px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($produits as $produit)
            <tr data-stock="{{ $produit->quantite < 10 ? 'low' : 'ok' }}" data-nom="{{ strtolower($produit->nomp) }}" data-id="{{ $produit->id }}">
                <td style="color:var(--text-muted);">{{ $produit->id }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="product-img-box">
                            @if($produit->image)
                                <img src="{{ asset($produit->image) }}" alt="{{ $produit->nomp }}">
                            @else
                                <i class="ti ti-package"></i>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13px;">{{ $produit->nomp }}</div>
                            @if($produit->description)
                                <div style="font-size:11px;color:var(--text-muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $produit->description }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge" style="background:#EFF6FF; color:#1D4ED8;">{{ $produit->category->nomcat ?? '—' }}</span>
                </td>
                <td>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                <td>
                    <span class="{{ $produit->quantite < 10 ? 'stock-low' : 'stock-ok' }}">
                        {{ $produit->quantite }}
                    </span>
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.super.produits.show', $produit) }}" class="action-btn view" title="Voir">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="{{ route('admin.super.produits.edit', $produit) }}" class="action-btn edit" title="Modifier">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" class="action-btn delete"
                                data-id="{{ $produit->id }}"
                                data-name="{{ $produit->nomp }}"
                                title="Supprimer">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">Aucun produit trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
 @if($produits->hasPages())
<div class="pagination-wrapper d-flex justify-content-end align-items-center gap-1 mt-3">
    @if ($produits->onFirstPage())
        <span class="btn btn-sm btn-outline-secondary disabled" style="border-color:var(--border-color);color:var(--text-muted);pointer-events:none;"><i class="ti ti-chevron-left"></i></span>
    @else
        <a href="{{ $produits->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary" style="border-color:var(--border-color);color:var(--text-secondary);"><i class="ti ti-chevron-left"></i></a>
    @endif

    @foreach ($produits->getUrlRange(1, $produits->lastPage()) as $page => $url)
        @if ($page == $produits->currentPage())
            <span class="btn btn-sm" style="background:var(--accent);color:#fff;border:none;font-weight:600;">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border-color);color:var(--text-secondary);">{{ $page }}</a>
        @endif
    @endforeach

    @if ($produits->hasMorePages())
        <a href="{{ $produits->nextPageUrl() }}" class="btn btn-sm btn-outline-secondary" style="border-color:var(--border-color);color:var(--text-secondary);"><i class="ti ti-chevron-right"></i></a>
    @else
        <span class="btn btn-sm btn-outline-secondary disabled" style="border-color:var(--border-color);color:var(--text-muted);pointer-events:none;"><i class="ti ti-chevron-right"></i></span>
    @endif
</div>
@endif

{{-- MODALE DE SUPPRESSION DOUCE --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow" style="border-radius: 1.2rem;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-dark fw-bold" id="deleteModalLabel">
          <i class="ti ti-trash text-danger me-2"></i>Supprimer le produit
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center pt-2">
        <p class="mb-1 text-secondary">Vous allez supprimer</p>
        <p class="fw-bold fs-5 text-dark" id="deleteProductName"></p>
        <p class="text-muted small">Cette action est <span class="text-danger">irréversible</span>.</p>
        <button id="softDeleteBtn" class="btn btn-outline-danger w-100 mt-3" disabled>
          <span id="btnText">Confirmer la suppression</span>
          <span id="btnTimer" class="ms-2 small"></span>
        </button>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    // ---------- MODALE DE SUPPRESSION ----------
    const deleteModal = document.getElementById('deleteModal');
    const softDeleteBtn = document.getElementById('softDeleteBtn');
    const btnText = document.getElementById('btnText');
    const btnTimer = document.getElementById('btnTimer');
    const deleteProductName = document.getElementById('deleteProductName');
    let deleteUrl = '';
    let countdown = 3;
    let timerInterval = null;

    deleteModal.addEventListener('show.bs.modal', function () {
      countdown = 3;
      softDeleteBtn.disabled = true;
      softDeleteBtn.classList.remove('btn-danger');
      softDeleteBtn.classList.add('btn-outline-danger');
      btnText.textContent = 'Confirmer la suppression';
      btnTimer.textContent = '';
      clearInterval(timerInterval);
      startCountdown();
    });

    function startCountdown() {
      timerInterval = setInterval(() => {
        if (countdown > 0) {
          btnTimer.textContent = `(${countdown}s)`;
          countdown--;
        } else {
          clearInterval(timerInterval);
          btnTimer.textContent = '';
          btnText.textContent = 'Supprimer définitivement';
          softDeleteBtn.disabled = false;
          softDeleteBtn.classList.remove('btn-outline-danger');
          softDeleteBtn.classList.add('btn-danger');
        }
      }, 1000);
    }

    softDeleteBtn.addEventListener('click', function () {
      if (!softDeleteBtn.disabled) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
      }
    });

    document.querySelectorAll('.action-btn.delete').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        deleteUrl = `/admin/super/produits/${id}`;
        deleteProductName.textContent = name;
        new bootstrap.Modal(deleteModal).show();
      });
    });

    // ---------- RECHERCHE ET FILTRES ----------
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('#tableBody tr[data-stock]');
    const visibleCounter = document.getElementById('visibleCounter');
    let currentFilter = 'all';

    function updateFilters() {
      const searchTerm = searchInput.value.toLowerCase();
      let visibleCount = 0;
      rows.forEach(row => {
        const stock = row.dataset.stock;
        const nom = row.dataset.nom || '';
        const matchFilter = (currentFilter === 'all' || stock === currentFilter);
        const matchSearch = nom.includes(searchTerm);
        if (matchFilter && matchSearch) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
      if (visibleCounter) visibleCounter.textContent = `${visibleCount} produit(s) affiché(s)`;
    }

    searchInput.addEventListener('input', updateFilters);

    filterButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        filterButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentFilter = this.dataset.stock;
        updateFilters();
      });
    });

    updateFilters();

    // ---------- TRI ----------
    document.querySelectorAll('th[data-sort]').forEach(th => {
      th.addEventListener('click', function() {
        const tbody = document.getElementById('tableBody');
        const col = Array.from(this.parentNode.children).indexOf(this);
        const type = this.dataset.sort;
        const rowsArray = Array.from(tbody.querySelectorAll('tr'));
        const asc = this.classList.contains('asc');
        rowsArray.sort((a, b) => {
          let aVal, bVal;
          if (type === 'id') {
            aVal = parseInt(a.dataset[type]) || 0;
            bVal = parseInt(b.dataset[type]) || 0;
            return asc ? bVal - aVal : aVal - bVal;
          } else {
            aVal = a.dataset[type] || '';
            bVal = b.dataset[type] || '';
            return asc ? bVal.localeCompare(aVal) : aVal.localeCompare(bVal);
          }
        });
        rowsArray.forEach(row => tbody.appendChild(row));
        this.classList.toggle('asc');
      });
    });
</script>
@endpush
