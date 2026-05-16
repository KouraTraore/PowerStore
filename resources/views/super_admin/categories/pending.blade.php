@extends('layouts.super_admin')

@section('title', 'Catégories en attente')
@section('page-title', 'Validation des catégories')
@section('breadcrumb', 'Super Admin › Catégories › En attente')

@push('styles')
<style>
    .btn-outline-custom {
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        background: var(--bg-card);
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        padding: 7px 14px;
    }
    .btn-outline-custom:hover {
        background: var(--bg-hover);
    }
    .btn-outline-custom i { font-size: 15px; }

    .category-img-box {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: var(--bg-hover);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }
    .category-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }
    .category-img-box i {
        font-size: 24px;
        color: var(--text-muted);
    }

    .badge-status {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        background: var(--bg);
        color: var(--color);
    }

    .btn-approve {
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
    }
    .btn-reject {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
    }

    .rejection-reason-box {
        background: #FEF2F2;
        border-left: 3px solid #ef4444;
        padding: 8px 12px;
        border-radius: 0 6px 6px 0;
        margin-bottom: 10px;
        font-size: 12px;
        color: #991B1B;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.4);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: var(--bg-card);
        border-radius: 14px;
        padding: 28px;
        max-width: 460px;
        width: 90%;
        box-shadow: 0 8px 32px rgba(0,0,0,.12);
    }
    .modal-close {
        border: none;
        background: none;
        font-size: 22px;
        color: var(--text-muted);
        cursor: pointer;
        line-height: 1;
    }
    .btn-modal-cancel {
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        color: var(--text-secondary);
    }
    .btn-modal-submit {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:2px">Catégories en attente</h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0">Approuvez ou rejetez les catégories soumises par les admins.</p>
    </div>
    <a href="{{ route('admin.super.categories.index') }}" class="btn-outline-custom d-flex align-items-center gap-1">
        <i class="ti ti-list"></i> Toutes les catégories
    </a>
</div>

@if($categories->isEmpty())
    <div class="card p-5 text-center" style="background:var(--bg-card);border:1px solid var(--border-color);color:var(--text-primary);">
        <i class="ti ti-circle-check" style="font-size:48px;color:var(--accent);margin-bottom:12px;display:block"></i>
        <h3 style="font-size:16px;font-weight:600;">Tout est à jour !</h3>
        <p style="font-size:13px;color:var(--text-muted);">Aucune catégorie en attente de validation.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($categories as $category)
        <div class="col-lg-6">
            <div class="card" style="background:var(--bg-card);border:1px solid var(--border-color);">
                <div class="d-flex align-items-start gap-3 p-4">
                    <div class="category-img-box">
                        @if($category->image)
                            <img src="{{ asset($category->image) }}" alt="{{ $category->nomcat }}">
                        @else
                            <i class="ti ti-category"></i>
                        @endif
                    </div>

                    <div style="flex:1;min-width:0">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h3 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0">{{ $category->nomcat }}</h3>
                            <span class="badge-status"
                                  style="--bg: {{ $category->status === 'pending' ? '#FEF3C7' : '#FEF2F2' }};
                                         --color: {{ $category->status === 'pending' ? '#92400E' : '#991B1B' }}">
                                {{ $category->status }}
                            </span>
                        </div>

                        <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px">
                            <i class="ti ti-user" style="font-size:13px"></i>
                            Créé par <strong style="color:var(--text-secondary)">{{ $category->creator->username ?? '—' }}</strong>
                            {{-- Pas de created_at --}}
                        </div>

                        @if($category->rejection_reason)
                            <div class="rejection-reason-box">
                                <strong>Motif rejet précédent :</strong> {{ $category->rejection_reason }}
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.super.categories.approve', $category) }}">
                                @csrf
                                <button type="submit" class="btn-approve d-flex align-items-center gap-1">
                                    <i class="ti ti-check" style="font-size:14px"></i> Approuver
                                </button>
                            </form>

                            <button type="button" class="btn-reject d-flex align-items-center gap-1"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ $category->nomcat }}">
                                <i class="ti ti-x" style="font-size:14px"></i> Rejeter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- MODAL REJET --}}
<div id="reject-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h3 style="font-size:16px;font-weight:700;color:var(--text-primary);margin:0">Rejeter la catégorie</h3>
            <button onclick="closeRejectModal()" class="modal-close">&times;</button>
        </div>
        <p style="font-size:13px;color:var(--text-secondary);margin-bottom:16px">
            Vous allez rejeter <strong id="reject-catname"></strong>. Veuillez indiquer la raison.
        </p>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-3">
                <label style="font-size:13px;font-weight:600;color:var(--text-primary);display:block;margin-bottom:6px">
                    Motif du rejet <span style="color:#ef4444">*</span>
                </label>
                <textarea name="reason" rows="3" required
                          style="width:100%;border:1px solid var(--border-color);border-radius:8px;padding:10px 12px;font-size:13px;font-family:inherit;resize:vertical;outline:none;background:var(--bg-card);color:var(--text-primary)"
                          placeholder="Ex: Catégorie déjà existante, nom incorrect..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeRejectModal()" class="btn-modal-cancel">Annuler</button>
                <button type="submit" class="btn-modal-submit">
                    <i class="ti ti-x"></i> Confirmer le rejet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showRejectModal(id, name) {
    document.getElementById('reject-catname').textContent = '"' + name + '"';
    document.getElementById('reject-form').action = '/admin/super/categories/' + id + '/reject';
    document.getElementById('reject-modal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('reject-modal').style.display = 'none';
}
document.getElementById('reject-modal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
document.querySelectorAll('.btn-reject').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.categoryId;
        const name = this.dataset.categoryName;
        showRejectModal(id, name);
    });
});
</script>
@endpush
