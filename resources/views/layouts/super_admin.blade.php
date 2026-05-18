<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Super Admin') — PowerStock</title>

    {{-- ═══════════════════════════════════════════
         FAVICONS
    ═══════════════════════════════════════════ --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon_io/favicon-16x16.png') }}">

    {{-- ═══════════════════════════════════════════
         CSS LIBRAIRIES
    ═══════════════════════════════════════════ --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- ═══════════════════════════════════════════
         VARIABLES CSS — THÈME CLAIR / SOMBRE
         Toutes les couleurs sont centralisées ici.
         On bascule le thème en changeant data-theme
         sur la balise <html>.
    ═══════════════════════════════════════════ --}}
    <style>
        /* ── THÈME CLAIR (défaut) ── */
        :root,
        [data-theme="light"] {
            --bg-page        : #f4f6f9;
            --bg-sidebar     : #ffffff;
            --bg-topbar      : #ffffff;
            --bg-card        : #ffffff;
            --bg-hover       : #f8fafc;
            --bg-active      : #fff7ed;

            --text-primary   : #1a1a2e;
            --text-secondary : #64748b;
            --text-muted     : #94a3b8;
            --text-active    : #c2410c;

            --border-color   : #eaecf0;
            --border-light   : #f1f5f9;

            --accent         : #f97316;       /* orange PowerStock */
            --accent-dark    : #ea580c;
            --accent-light   : #fff7ed;
            --accent-border  : #fed7aa;

            --sidebar-width  : 224px;
            --sidebar-collapsed: 58px;
            --topbar-height  : 54px;

            --shadow-sm      : 0 1px 3px rgba(0,0,0,.06);
            --shadow-md      : 0 4px 12px rgba(0,0,0,.08);
        }

        /* ── THÈME SOMBRE ── */
        [data-theme="dark"] {
            --bg-page        : #0f1117;
            --bg-sidebar     : #1a1d27;
            --bg-topbar      : #1a1d27;
            --bg-card        : #1e2130;
            --bg-hover       : #252838;
            --bg-active      : #2d2318;

            --text-primary   : #e2e8f0;
            --text-secondary : #94a3b8;
            --text-muted     : #64748b;
            --text-active    : #fb923c;

            --border-color   : #2d3348;
            --border-light   : #252838;

            --accent         : #f97316;
            --accent-dark    : #ea580c;
            --accent-light   : #2d2318;
            --accent-border  : #7c3010;

            --shadow-sm      : 0 1px 3px rgba(0,0,0,.3);
            --shadow-md      : 0 4px 12px rgba(0,0,0,.4);
        }

        /* ═══════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 13px;
            background: var(--bg-page);
            color: var(--text-primary);
            transition: background .25s, color .25s;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════════
           OVERLAY — visible sur mobile quand sidebar ouverte
        ═══════════════════════════════════════════ */
        #overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 200;
            transition: opacity .25s;
        }
        #overlay.show { display: block; }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 300;
            overflow: hidden;
            overflow-y: auto;
            transition: width .22s ease, transform .22s ease, background .25s, border-color .25s;
        }

        /* ── Collapsed (desktop) ── */
        #sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        /* ── Caché par défaut sur mobile ── */
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important; /* toujours pleine largeur sur mobile */
            }
            #sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        /* ── Logo / Brand ── */
        .sb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            height: var(--topbar-height);
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            overflow: hidden;
            flex-shrink: 0;
            transition: border-color .25s;
        }

        .sb-logo-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800;
            flex-shrink: 0;
            letter-spacing: -.5px;
        }

        /* Logo texte : caché quand collapsed */
        .sb-logo-text {
            display: flex; flex-direction: column;
            overflow: hidden;
            transition: opacity .18s, width .22s;
        }
        .sb-logo-name {
            font-size: 14px; font-weight: 700;
            color: var(--accent);
            line-height: 1.2;
        }
        .sb-logo-badge {
            font-size: 9px;
            background: var(--accent-light);
            color: var(--text-active);
            border: 1px solid var(--accent-border);
            padding: 1px 7px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-top: 2px;
            width: fit-content;
        }
        #sidebar.collapsed .sb-logo-text {
            opacity: 0; width: 0;
        }
.sb-logo-img { flex-shrink: 0; }
#sidebar.collapsed .logo-full { display: none; }
#sidebar:not(.collapsed) .logo-mini { display: none; }

        /* ── Navigation section label ── */
        .sb-section {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 14px 16px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity .18s;
        }
        #sidebar.collapsed .sb-section { opacity: 0; }

        /* ── Nav item ── */
        .nav-item-sa {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s, border-color .15s;
            position: relative;
        }
        .nav-item-sa:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-left-color: var(--border-light);
        }
        .nav-item-sa.active {
            background: var(--bg-active);
            color: var(--text-active);
            border-left-color: var(--accent);
        }
        .nav-item-sa.active i { color: var(--accent); }

        /* ── Icône nav ── */
        .nav-item-sa i {
            font-size: 17px;
            width: 18px;
            flex-shrink: 0;
        }

        /* ── Texte nav : caché quand collapsed ── */
        .nav-text-sa {
            flex: 1;
            transition: opacity .18s, width .22s;
            overflow: hidden;
        }
        #sidebar.collapsed .nav-text-sa {
            opacity: 0; width: 0;
        }

        /* ── Badge nav (ex: "3 en attente") ── */
        .nav-badge {
            font-size: 10px; font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            flex-shrink: 0;
            transition: opacity .18s;
        }
        .nav-badge.warning { background: #fef3c7; color: #92400e; }
        .nav-badge.danger  { background: #fee2e2; color: #991b1b; }
        #sidebar.collapsed .nav-badge { opacity: 0; pointer-events: none; }

        /* ── Tooltip quand collapsed (desktop) ── */
        #sidebar.collapsed .nav-item-sa::after {
            content: attr(data-label);
            position: absolute;
            left: calc(var(--sidebar-collapsed) + 8px);
            top: 50%; transform: translateY(-50%);
            background: var(--text-primary);
            color: var(--bg-card);
            font-size: 11px; font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity .15s;
            z-index: 999;
        }
        #sidebar.collapsed .nav-item-sa:hover::after { opacity: 1; }

        /* ── Divider ── */
        .sb-divider {
            height: 1px;
            background: var(--border-color);
            margin: 6px 14px;
            transition: background .25s;
        }

        /* ── Footer utilisateur ── */
        .sb-footer {
            margin-top: auto;
            padding: 12px 14px;
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
            transition: border-color .25s;
        }
        .sb-user-row {
            display: flex;
            align-items: center;
            gap: 9px;
            overflow: hidden;
        }
        .sb-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
            flex-shrink: 0;
        }
        .sb-user-info {
            flex: 1; min-width: 0;
            transition: opacity .18s, width .22s;
            overflow: hidden;
        }
        #sidebar.collapsed .sb-user-info { opacity: 0; width: 0; }
        .sb-user-name {
            font-size: 12px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            color: var(--text-primary);
        }
        .sb-user-role {
            font-size: 10px; color: var(--text-muted);
        }

        /* ═══════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════ */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--bg-topbar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 100;
            transition: left .22s ease, background .25s, border-color .25s;
        }

        /* Quand la sidebar est collapsed, la topbar s'élargit */
        #topbar.expanded {
            left: var(--sidebar-collapsed);
        }

        /* Sur mobile, la topbar part du bord gauche */
        @media (max-width: 991px) {
            #topbar { left: 0 !important; }
        }

        /* ── Bouton toggle sidebar ── */
        .toggle-btn {
            width: 34px; height: 34px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-card);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: background .15s, border-color .15s;
            flex-shrink: 0;
        }
        .toggle-btn:hover { background: var(--bg-hover); }

        /* ── Titre de la page ── */
        .topbar-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .topbar-sub   { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

        /* ── Boutons topbar droite ── */
        .topbar-right { display: flex; align-items: center; gap: 8px; }

        .topbar-icon-btn {
            width: 34px; height: 34px;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            background: var(--bg-card);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 17px;
            position: relative;
            transition: background .15s;
        }
        .topbar-icon-btn:hover { background: var(--bg-hover); }

        /* Point rouge notification */
        .notif-dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 7px; height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid var(--bg-topbar);
        }

        /* ── Bouton dark mode ── */
        .theme-btn {
            width: 34px; height: 34px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-card);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 17px;
            transition: background .15s, border-color .15s;
        }
        .theme-btn:hover { background: var(--bg-hover); }

        /* ── Avatar topbar ── */
        .topbar-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
            cursor: pointer;
        }

        /* ═══════════════════════════════════════════
           CONTENU PRINCIPAL
        ═══════════════════════════════════════════ */
        #main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 24px;
            min-height: calc(100vh - var(--topbar-height));
            transition: margin-left .22s ease;
        }

        /* Quand sidebar collapsed */
        #main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        /* Sur mobile : plus de margin-left */
        @media (max-width: 991px) {
            #main-content { margin-left: 0 !important; }
        }

        /* ═══════════════════════════════════════════
           COMPOSANTS RÉUTILISABLES
        ═══════════════════════════════════════════ */

        /* ── Cartes ── */
        .sa-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: background .25s, border-color .25s;
        }
        .sa-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-light);
            transition: border-color .25s;
        }
        .sa-card-title {
            font-size: 13px; font-weight: 600;
            color: var(--text-primary);
            display: flex; align-items: center; gap: 7px;
        }
        .sa-card-action {
            font-size: 11px; font-weight: 600;
            color: var(--accent); cursor: pointer;
            text-decoration: none;
        }
        .sa-card-action:hover { color: var(--accent-dark); }

        /* ── KPI Cards ── */
        .kpi-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
            transition: background .25s, border-color .25s, transform .15s;
        }
        .kpi-card:hover { transform: translateY(-1px); }
        /* Barre colorée en haut de chaque KPI */
        .kpi-accent-bar {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }
        .kpi-label {
            font-size: 11px; font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 6px;
        }
        .kpi-value {
            font-size: 26px; font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }
        .kpi-value.sm { font-size: 17px; margin-top: 3px; }
        .kpi-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-light);
        }
        .kpi-sub  { font-size: 11px; color: var(--text-muted); }
        .kpi-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 21px;
        }

        /* ── Tables ── */
        .sa-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .sa-table thead th {
            font-size: 10px; font-weight: 600;
            color: var(--text-muted);
            text-align: left;
            padding: 9px 18px;
            text-transform: uppercase;
            letter-spacing: .06em;
            background: var(--bg-hover);
            border-bottom: 1px solid var(--border-light);
        }
        .sa-table tbody td {
            padding: 10px 18px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            color: var(--text-primary);
        }
        .sa-table tbody tr:last-child td { border-bottom: none; }
        .sa-table tbody tr:hover td { background: var(--bg-hover); }

        /* ── Badges / Pills ── */
        .pill {
            font-size: 10px; padding: 3px 9px;
            border-radius: 20px; font-weight: 600;
            display: inline-block; border: 1px solid transparent;
        }
        .pill-sa       { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
        .pill-admin    { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .pill-active   { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .pill-inactive { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .pill-pending  { background: #fefce8; color: #854d0e; border-color: #fde68a; }
        .pill-approved { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .pill-rejected { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

        /* ── Boutons ── */
        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px; font-weight: 600;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s;
            text-decoration: none;
        }
        .btn-accent:hover { background: var(--accent-dark); color: #fff; }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px; font-weight: 500;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s, border-color .15s;
            text-decoration: none;
        }
        .btn-outline:hover { background: var(--bg-hover); border-color: var(--text-muted); color: var(--text-primary); }

        /* ── Alerts flash ── */
        .alert-sa {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 13px;
        }
        .alert-sa.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-sa.danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-sa.warning { background: #fff7ed; border: 1px solid var(--accent-border); color: #92400e; }

        /* ── Banner alerte en attente ── */
        .pending-banner {
            background: var(--accent-light);
            border: 1px solid var(--accent-border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
            display: flex; align-items: center; gap: 12px;
            transition: background .25s, border-color .25s;
        }
        .pending-banner i { color: var(--accent); font-size: 19px; flex-shrink: 0; }
        .pending-banner .pb-title { font-size: 12px; font-weight: 700; color: var(--text-active); }
        .pending-banner .pb-sub   { font-size: 11px; color: #92400e; margin-top: 1px; }
        [data-theme="dark"] .pending-banner .pb-sub { color: #fb923c; }

        /* ── Responsive : masquer certains éléments sur petit écran ── */
        @media (max-width: 575px) {
            #main-content { padding: 16px 14px; }
            .hide-xs { display: none !important; }
        }

        /* ── Scrollbar personnalisée ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
    </style>

    @stack('styles')
</head>

<body>

{{-- ═══════════════════════════════════════════════════════
     OVERLAY MOBILE
     Cliquable pour fermer la sidebar sur téléphone.
═══════════════════════════════════════════════════════ --}}
<div id="overlay" onclick="closeMobileSidebar()"></div>

{{-- ═══════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════ --}}
<aside id="sidebar">

    {{-- ── Logo PowerStock ── --}}
 <div class="sb-brand">
    <img src="{{ asset('images/logo-full.svg') }}" class="sb-logo-img logo-full" alt="Logo" width="36" height="36">
    <img src="{{ asset('images/logo-mini.png') }}" class="sb-logo-img logo-mini" alt="Logo" width="24" height="24">
    <div class="sb-logo-text">
        <span class="sb-logo-name">PowerStock</span>
        <span class="sb-logo-badge">Super Admin</span>
    </div>
</div>

    {{-- ── Navigation principale ── --}}
    <div class="sb-section">Principal</div>
    <a href="{{ route('admin.super.dashboard') }}"
       class="nav-item-sa {{ request()->routeIs('admin.super.dashboard') ? 'active' : '' }}"
       data-label="Dashboard">
        <i class="ti ti-layout-dashboard"></i>
        <span class="nav-text-sa">Tableau de bord</span>
    </a>

    {{-- ── Gestion ── --}}
    <div class="sb-section">Gestion</div>

    <a href="{{ route('admin.super.users.index') }}"
       class="nav-item-sa {{ request()->routeIs('admin.super.users.*') ? 'active' : '' }}"
       data-label="Utilisateurs">
        <i class="ti ti-users"></i>
        <span class="nav-text-sa">Utilisateurs</span>
    </a>

    <a href="{{ route('admin.super.categories.index') }}"
       class="nav-item-sa {{ request()->routeIs('admin.super.categories.*') ? 'active' : '' }}"
       data-label="Catégories">
        <i class="ti ti-category"></i>
        <span class="nav-text-sa">Catégories</span>
        @php
            /* On calcule les catégories en attente pour le badge */
            $pendingCount = \App\Models\Category::where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
            <span class="nav-badge warning">{{ $pendingCount }}</span>
        @endif
    </a>

    <a href="{{ route('admin.super.categories.pending') }}"
       class="nav-item-sa {{ request()->routeIs('admin.super.categories.pending') ? 'active' : '' }}"
       data-label="En attente">
        <i class="ti ti-clock-check"></i>
        <span class="nav-text-sa">En attente</span>
        @if($pendingCount > 0)
            <span class="nav-badge danger">{{ $pendingCount }}</span>
        @endif
    </a>

    {{-- Produits — géré par le super admin aussi --}}
   <a href="{{ route('admin.super.produits.index') }}"
   class="nav-item-sa {{ request()->routeIs('admin.super.produits.*') ? 'active' : '' }}"
   data-label="Produits">
    <i class="ti ti-package"></i>
    <span class="nav-text-sa">Produits</span>
</a>

    <div class="sb-divider"></div>

    {{-- ── Administration ── --}}
    <div class="sb-section">Administration</div>
    <a href="{{ route('admin.index') }}" class="nav-item-sa" data-label="Dashboard Admin">
        <i class="ti ti-home"></i>
        <span class="nav-text-sa">Dashboard Admin</span>
    </a>

    {{-- ── Compte ── --}}
    <div class="sb-section">Compte</div>
    <a href="{{ route('logout') }}"
       class="nav-item-sa"
       style="color: #ef4444"
       data-label="Déconnexion"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="ti ti-logout" style="color:#ef4444"></i>
        <span class="nav-text-sa">Déconnexion</span>
    </a>

    {{-- ── Footer utilisateur ── --}}
    <div class="sb-footer">
        <div class="sb-user-row">
            <div class="sb-avatar">
                {{ strtoupper(substr(auth()->user()->prenom ?? 'S', 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom ?? 'A', 0, 1)) }}
            </div>
            <div class="sb-user-info">
                <div class="sb-user-name">{{ auth()->user()->username }}</div>
                <div class="sb-user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
    </div>

</aside>

{{-- ═══════════════════════════════════════════════════════
     TOPBAR
═══════════════════════════════════════════════════════ --}}
<nav id="topbar">

    <div class="d-flex align-items-center gap-2">

        {{-- Bouton toggle sidebar (desktop : collapse / mobile : ouvrir) --}}
        <button class="toggle-btn" id="toggleBtn" onclick="handleToggle()" aria-label="Ouvrir/fermer le menu">
            <i class="ti ti-layout-sidebar-left-expand" id="toggleIcon"></i>
        </button>

        {{-- Titre de la page courante --}}
        <div class="ms-1 d-none d-sm-block">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-sub">Super Admin · PowerStock</div>
        </div>
    </div>

    {{-- Droite : dark mode + notifications + avatar --}}
    <div class="topbar-right">

        {{-- Bouton dark/light mode --}}
        <button class="theme-btn" id="themeBtn" onclick="toggleTheme()" aria-label="Changer de thème">
            <i class="ti ti-sun" id="themeIcon"></i>
        </button>

        {{-- Cloche notifications --}}
        <div class="topbar-icon-btn" title="Notifications">
            <i class="ti ti-bell"></i>
            @if(isset($pendingCount) && $pendingCount > 0)
                <span class="notif-dot"></span>
            @endif
        </div>

        {{-- Avatar utilisateur --}}
        <div class="topbar-avatar" title="{{ auth()->user()->username }}">
            {{ strtoupper(substr(auth()->user()->prenom ?? 'S', 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom ?? 'A', 0, 1)) }}
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════
     CONTENU PRINCIPAL
═══════════════════════════════════════════════════════ --}}
<main id="main-content">

    {{-- ── Flash messages ── --}}
    @if(session('success'))
        <div class="alert-sa success">
            <i class="ti ti-circle-check" style="font-size:17px;flex-shrink:0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-sa danger">
            <i class="ti ti-alert-circle" style="font-size:17px;flex-shrink:0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert-sa warning">
            <i class="ti ti-alert-triangle" style="font-size:17px;flex-shrink:0"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- ── Contenu de la page ── --}}
    @yield('content')

    {{-- ── Footer ── --}}
    <footer class="text-center mt-4 pb-3" style="font-size:11px;color:var(--text-muted)">
        &copy; {{ date('Y') }} PowerStock — Super Admin Panel
    </footer>

</main>

{{-- Formulaire logout caché --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>

{{-- ═══════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /*
    ═══════════════════════════════════════════════════════
     GESTION DE LA SIDEBAR
     - Desktop : collapse (icônes seules) / expanded (icônes + texte)
     - Mobile  : slide-in depuis la gauche + overlay
    ═══════════════════════════════════════════════════════
    */

    const sidebar     = document.getElementById('sidebar');
    const topbar      = document.getElementById('topbar');
    const mainContent = document.getElementById('main-content');
    const toggleIcon  = document.getElementById('toggleIcon');
    const overlay     = document.getElementById('overlay');

    /* État sauvegardé en localStorage pour persister entre pages */
    let isCollapsed = localStorage.getItem('sb_collapsed') === 'true';

    /* Applique l'état initial au chargement */
    function applySidebarState() {
        const isMobile = window.innerWidth < 992;
        if (isMobile) {
            /* Sur mobile on ne tient pas compte du localStorage */
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.classList.toggle('collapsed', isCollapsed);
            topbar.classList.toggle('expanded', isCollapsed);
            mainContent.classList.toggle('expanded', isCollapsed);
            toggleIcon.className = isCollapsed
                ? 'ti ti-layout-sidebar-left-collapse'
                : 'ti ti-layout-sidebar-left-expand';
        }
    }

    /* Appelé par le bouton toggle */
    function handleToggle() {
        const isMobile = window.innerWidth < 992;
        if (isMobile) {
            openMobileSidebar();
        } else {
            isCollapsed = !isCollapsed;
            localStorage.setItem('sb_collapsed', isCollapsed);
            applySidebarState();
        }
    }

    /* Ouvre la sidebar sur mobile */
    function openMobileSidebar() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden'; /* bloque le scroll */
    }

    /* Ferme la sidebar sur mobile (clic overlay) */
    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    /* Recalcule l'état si on redimensionne la fenêtre */
    window.addEventListener('resize', applySidebarState);

    /* Init */
    applySidebarState();


    /*
    ═══════════════════════════════════════════════════════
     GESTION DU THÈME SOMBRE / CLAIR
     - Sauvegardé en localStorage
     - Appliqué sur <html data-theme="...">
    ═══════════════════════════════════════════════════════
    */

    const html      = document.documentElement;
    const themeIcon = document.getElementById('themeIcon');

    /* Récupère le thème sauvegardé, sinon préférence système */
    function getSavedTheme() {
        const saved = localStorage.getItem('ps_theme');
        if (saved) return saved;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /* Applique le thème */
    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        /* Icône : soleil en dark mode, lune en light mode */
        themeIcon.className = theme === 'dark' ? 'ti ti-moon' : 'ti ti-sun';
        localStorage.setItem('ps_theme', theme);
    }

    /* Bascule entre les deux thèmes */
    function toggleTheme() {
        const current = html.getAttribute('data-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    /* Init thème */
    applyTheme(getSavedTheme());
</script>

@stack('scripts')
</body>
</html>
