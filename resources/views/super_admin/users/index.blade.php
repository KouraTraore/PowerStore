@extends('layouts.super_admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('breadcrumb', 'Super Admin › Utilisateurs')

@push('styles')
<style>
    .stat-box {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    .stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 600;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .stat-value.green { color: #166534; }
    .stat-value.red   { color: #991B1B; }
    .stat-value.blue  { color: #1D4ED8; }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }
    .filter-btn {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-btn.active,
    .filter-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .search-box {
        display: flex;
        align-items: center;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0 10px;
    }
    .search-box input {
        border: none;
        background: transparent;
        padding: 8px 0;
        outline: none;
        color: var(--text-primary);
        width: 180px;
    }
    .search-box i { color: var(--text-muted); }

    .table-container {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        background: var(--bg-card);
    }
    .btn-action {
    border: 1px solid var(--border-color);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    background: var(--bg-card);
    color: var(--color, var(--text-secondary));
    cursor: pointer;
    transition: background .15s, color .15s;
}
.btn-action:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:2px">Utilisateurs</h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0">Gérez tous les comptes administrateurs de la plateforme.</p>
    </div>
    <a href="{{ route('admin.super.users.create') }}" class="btn-accent">
        <i class="ti ti-plus" style="font-size:15px"></i> Nouvel utilisateur
    </a>
</div>

{{-- STATS RAPIDES (cliquables) --}}
<div class="row g-3 mb-4">
    <div class="col-sm-3 fade-up">
        <a href="#" class="stat-box">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $users->total() }}</div>
        </a>
    </div>
    <div class="col-sm-3 fade-up">
        <a href="#" class="stat-box">
            <div class="stat-label">Actifs</div>
            <div class="stat-value green">{{ $users->where('is_active', 1)->count() }}</div>
        </a>
    </div>
    <div class="col-sm-3 fade-up">
        <a href="#" class="stat-box">
            <div class="stat-label">Inactifs</div>
            <div class="stat-value red">{{ $users->where('is_active', 0)->count() }}</div>
        </a>
    </div>
    <div class="col-sm-3 fade-up">
        <a href="#" class="stat-box">
            <div class="stat-label">Super Admins</div>
            <div class="stat-value blue">{{ $users->where('role', 'super_admin')->count() }}</div>
        </a>
    </div>
</div>

{{-- RECHERCHE & FILTRES --}}
<div class="filter-bar mb-3">
    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Rechercher...">
    </div>
    <div>
        <button class="filter-btn active" data-filter="all">Tous</button>
        <button class="filter-btn" data-filter="super_admin">Super Admin</button>
        <button class="filter-btn" data-filter="admin">Admin</button>
        <button class="filter-btn" data-filter="active" data-type="status">Actifs</button>
        <button class="filter-btn" data-filter="inactive" data-type="status">Inactifs</button>
    </div>
    <span class="ms-auto text-muted small" id="visibleCount"></span>
</div>

{{-- TABLE --}}
<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">
            <i class="ti ti-users" style="color:#1D4ED8;"></i> Liste des utilisateurs
        </div>
        <span style="font-size:12px;color:var(--text-muted)">{{ $users->total() }} utilisateurs</span>
    </div>
    <div class="table-responsive">
        <table class="sa-table" id="userTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $user)
                <tr class="user-row" data-role="{{ $user->role }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}" data-name="{{ strtolower($user->username) }}">
                    <td style="color:var(--text-muted);font-size:12px">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle-sm" style="--bg: {{ $user->role === 'super_admin' ? '#1D9E75' : '#1D4ED8' }}">
                                {{ strtoupper(substr($user->prenom ?? $user->username, 0, 1)) }}{{ strtoupper(substr($user->nom ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px">
                                    {{ $user->username }}
                                    @if($user->id === auth()->id())
                                        <span class="pill pill-sa" style="font-size:10px;padding:1px 6px;margin-left:4px">Vous</span>
                                    @endif
                                </div>
                                <div style="font-size:11px;color:var(--text-muted)">{{ $user->email }}</div>
                                @if($user->nom || $user->prenom)
                                    <div style="font-size:11px;color:var(--text-muted)">{{ $user->prenom }} {{ $user->nom }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="pill pill-{{ $user->role === 'super_admin' ? 'sa' : 'admin' }}">
                            {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                        </span>
                    </td>
                    <td>
                        <span class="pill pill-{{ $user->is_active ? 'active' : 'inactive' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">
                        {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">
                        {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Jamais' }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            {{-- Modifier --}}
                            <a href="{{ route('admin.super.users.edit', $user) }}" class="btn btn-action" title="Modifier">
                                <i class="ti ti-edit"></i>
                            </a>

                            {{-- Toggle actif/inactif --}}
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.super.users.toggle-active', $user) }}" class="d-inline toggle-form">
                                @csrf
                                <button type="submit"
                                        class="btn btn-action toggle-user"
                                        style="--color: {{ $user->is_active ? '#991B1B' : '#1D9E75' }}"
                                        title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}"
                                        data-confirm="{{ $user->is_active ? 'Désactiver' : 'Activer' }} cet utilisateur ?">
                                    <i class="ti ti-{{ $user->is_active ? 'user-off' : 'user-check' }}"></i>
                                </button>
                            </form>

                            {{-- Supprimer (modal douce) --}}
                            <button type="button" class="btn btn-action" style="--color: #991B1B; border-color: #FEE2E2;"
                                    onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->username) }}')"
                                    title="Supprimer">
                                <i class="ti ti-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5" style="color:var(--text-muted)">
                        <i class="ti ti-users-off" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- MODALE DE SUPPRESSION DOUCE (identique à celle des catégories) --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow" style="border-radius: 1.2rem;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-dark fw-bold" id="deleteModalLabel">
          <i class="ti ti-trash text-danger me-2"></i>Supprimer l'utilisateur
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center pt-2">
        <p class="mb-1 text-secondary">Vous allez supprimer</p>
        <p class="fw-bold fs-5 text-dark" id="deleteUserName"></p>
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
    const deleteUserName = document.getElementById('deleteUserName');
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

    function openDeleteModal(id, name) {
      deleteUrl = `/admin/super/users/${id}`;
      deleteUserName.textContent = name;
      new bootstrap.Modal(deleteModal).show();
    }

    // ---------- CONFIRMATION TOGGLE ----------
    document.querySelectorAll('.toggle-user').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm(btn.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // ---------- RECHERCHE & FILTRES ----------
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('#userTableBody .user-row');
    const visibleCount = document.getElementById('visibleCount');

    let currentFilter = 'all';
    let currentType = 'role'; // par défaut on filtre par rôle

    function filterRows() {
        const search = searchInput.value.toLowerCase();
        let count = 0;
        rows.forEach(row => {
            const role = row.dataset.role;
            const status = row.dataset.status;
            const name = row.dataset.name;
            let match = false;

            if (currentType === 'role') {
                match = (currentFilter === 'all' || role === currentFilter);
            } else if (currentType === 'status') {
                match = (currentFilter === 'all' || status === currentFilter);
            }

            if (match) {
                const textMatch = name.includes(search);
                if (textMatch) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            } else {
                row.style.display = 'none';
            }
        });
        if (visibleCount) visibleCount.textContent = `${count} utilisateur(s) affiché(s)`;
    }

    searchInput.addEventListener('input', filterRows);

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            currentType = this.dataset.type || 'role';
            filterRows();
        });
    });

    filterRows(); // initial call
</script>
@endpush
