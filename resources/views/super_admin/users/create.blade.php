@extends('layouts.super_admin')

@section('title', 'Créer un utilisateur')
@section('page-title', 'Nouvel utilisateur')
@section('breadcrumb', 'Super Admin › Utilisateurs › Créer')

@push('styles')
<style>
     .btn-back {
        width: 34px; height: 34px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-secondary); text-decoration: none;
    }
    .btn-back i { font-size: 17px; }

    .avatar-circle-md {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: var(--bg);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 600;
    }

    .badge-status {
        font-size: 11px; padding: 3px 10px; border-radius: 20px;
        font-weight: 600; background: var(--bg); color: var(--color);
    }

    .role-card {
        border-radius: 10px; padding: 14px;
        border: 2px solid var(--border-color, #EAECF0);
        background: var(--bg-card, #fff);
        transition: all .15s;
    }
    .role-card .role-icon {
        width: 34px; height: 34px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: var(--icon-bg); color: var(--icon-color); font-size: 18px;
    }
    .role-card .role-title {
        font-size: 13px; font-weight: 600; color: var(--text-primary);
    }
    .role-card .role-desc {
        font-size: 11px; color: var(--text-muted);
    }

    .input-group-text-custom {
        background: var(--bg-hover);
        border-color: var(--border-color);
        color: var(--text-muted);
    }
    .input-group-text-custom i { font-size: 16px; color: var(--text-muted); }

    .btn-cancel {
        border: 1px solid var(--border-color);
        padding: 8px 18px; border-radius: 8px;
        font-size: 13px; color: var(--text-secondary); text-decoration: none;
    }
    .btn-save {
        padding: 8px 18px; border-radius: 8px; font-size: 13px;
    }

    /* Bootstrap overrides pour le thème */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    .card-header {
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    .form-label {
        color: var(--text-primary);
    }
    .form-control, .form-select {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    .form-control:focus, .form-select:focus {
        background: var(--bg-card);
        border-color: var(--accent);
        color: var(--text-primary);
    }
    .input-group-text {
        background: var(--bg-hover);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
    }
    .invalid-feedback { color: #ef4444; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.super.users.index') }}" class="btn-back">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div>
                <h1 style="font-size:18px;font-weight:700;color:#1a1a2e;margin:0">Créer un utilisateur</h1>
                <p style="font-size:13px;color:#8a8fa8;margin:0">Ajouter un nouveau compte administrateur</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span><i class="ti ti-user-plus me-2" style="color:#1D4ED8"></i>Informations du compte</span>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.super.users.store') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">Prénom</label>
                            <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom') }}" placeholder="ex: Mamadou">
                            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">Nom</label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" placeholder="ex: Coulibaly">
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            Nom d'utilisateur <span style="color:#ef4444">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom">
                                <i class="ti ti-at"></i>
                            </span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" placeholder="ex: admin_mali" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            Adresse email <span style="color:#ef4444">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom">
                                <i class="ti ti-mail"></i>
                            </span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="ex: admin@electroshop.ml" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            Mot de passe <span style="color:#ef4444">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom">
                                <i class="ti ti-lock"></i>
                            </span>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimum 6 caractères" required>
                            <button type="button" class="input-group-text input-group-text-custom" style="cursor:pointer"
                                    onclick="togglePassword()">
                                <i class="ti ti-eye" id="eye-icon"></i>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div style="font-size:11px;color:#8a8fa8;margin-top:4px">Minimum 6 caractères.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            Rôle <span style="color:#ef4444">*</span>
                        </label>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="d-block" style="cursor:pointer">
                                    <input type="radio" name="role" value="admin"
                                           {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}
                                           class="d-none role-radio" id="role-admin">
                                    <div class="role-card" id="card-admin"
                                         style="--border-color: {{ old('role', 'admin') === 'admin' ? '#1D4ED8' : '#EAECF0' }};
                                                --bg: {{ old('role', 'admin') === 'admin' ? '#EFF6FF' : '#fff' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="role-icon" style="--icon-bg: #EFF6FF; --icon-color: #1D4ED8">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <div>
                                                <div class="role-title">Admin</div>
                                                <div class="role-desc">Gestion produits, factures, clients</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="d-block" style="cursor:pointer">
                                    <input type="radio" name="role" value="super_admin"
                                           {{ old('role') === 'super_admin' ? 'checked' : '' }}
                                           class="d-none role-radio" id="role-super">
                                    <div class="role-card" id="card-super"
                                         style="--border-color: {{ old('role') === 'super_admin' ? '#1D9E75' : '#EAECF0' }};
                                                --bg: {{ old('role') === 'super_admin' ? '#E1F5EE' : '#fff' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="role-icon" style="--icon-bg: #E1F5EE; --icon-color: #1D9E75">
                                                <i class="ti ti-shield-check"></i>
                                            </div>
                                            <div>
                                                <div class="role-title">Super Admin</div>
                                                <div class="role-desc">Accès complet à la plateforme</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.super.users.index') }}" class="btn-cancel">Annuler</a>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="ti ti-check me-1"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
    }
}

document.querySelectorAll('.role-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const cardAdmin = document.getElementById('card-admin');
        const cardSuper = document.getElementById('card-super');
        const isAdmin = document.getElementById('role-admin').checked;
        cardAdmin.style.setProperty('--border-color', isAdmin ? '#1D4ED8' : '#EAECF0');
        cardAdmin.style.setProperty('--bg', isAdmin ? '#EFF6FF' : '#fff');
        cardSuper.style.setProperty('--border-color', !isAdmin ? '#1D9E75' : '#EAECF0');
        cardSuper.style.setProperty('--bg', !isAdmin ? '#E1F5EE' : '#fff');
    });
});
</script>
@endpush
