@extends('layouts.super_admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('breadcrumb', 'Super Admin · Dashboard')

@push('styles')
<style>
    /* ----- Barres de progression ----- */
    .progress-thin { height: 4px; border-radius: 2px; background: #F2F4F7; }
    .progress-fill { height: 100%; border-radius: 2px; transition: width 0.4s; }

    /* ----- Animations ----- */
    .fade-up { animation: fadeUp 0.4s ease backwards; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    /* ----- Bandes d'alerte ----- */
    .alert-banner {
        border-radius: 10px; padding: 12px 18px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
    }
    .alert-banner i { font-size: 22px; }

    /* ----- KPI Cards ----- */
    .kpi-card {
        background: var(--bg-card, #fff);
        border: 1px solid var(--border-color, #EAECF0);
        border-radius: 14px; padding: 18px;
        position: relative; transition: all 0.2s;
        text-decoration: none; color: inherit; display: block;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md, 0 6px 14px rgba(0,0,0,0.03));
    }
    .kpi-accent-bar {
        position: absolute; top: 0; left: 0; width: 100%; height: 4px; border-radius: 14px 14px 0 0;
    }
    .kpi-label {
        font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em;
        font-weight: 600; color: var(--text-muted, #8a8fa8); margin-bottom: 6px;
    }
    .kpi-value { font-size: 28px; font-weight: 800; color: var(--text-primary, #1a1a2e); line-height: 1.2; }
    .kpi-value.sm { font-size: 22px; }
    .kpi-sub { font-size: 11px; color: var(--text-muted, #8a8fa8); }
    .kpi-icon {
        width: 38px; height: 38px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
    .kpi-footer {
        margin-top: 12px; padding-top: 10px;
        border-top: 1px solid var(--border-light, #F2F4F7);
        display: flex; justify-content: space-between; align-items: center;
    }

    /* ----- Cartes générales (SA-CARD) ----- */
    .sa-card {
        background: var(--bg-card, #fff);
        border: 1px solid var(--border-color, #EAECF0);
        border-radius: 14px; overflow: hidden;
    }
    .sa-card-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 16px 20px; border-bottom: 1px solid var(--border-light, #F2F4F7);
        background: var(--bg-card, #FCFDFD);
    }
    .sa-card-title {
        font-weight: 700; font-size: 14px;
        color: var(--text-primary, #1a1a2e);
        display: flex; align-items: center; gap: 8px;
    }
    .sa-card-action {
        font-size: 12px; color: var(--accent, #1D9E75);
        text-decoration: none; font-weight: 500;
    }
    .sa-card-action:hover { text-decoration: underline; }

    /* ----- Tableaux ----- */
    .sa-table { width: 100%; border-collapse: collapse; }
    .sa-table th {
        text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600;
        color: var(--text-muted, #8a8fa8); background: var(--bg-card, #FCFDFD);
        border-bottom: 1px solid var(--border-light, #F2F4F7);
    }
    .sa-table td {
        padding: 10px 16px; font-size: 12px;
        border-bottom: 1px solid var(--border-light, #F2F4F7); vertical-align: middle;
    }

    /* ----- Pills / Badges ----- */
    .pill {
        font-size: 11px; font-weight: 600; padding: 3px 10px;
        border-radius: 20px; display: inline-block;
    }
    .pill-sa { background: #E1F5EE; color: #0F6E56; }
    .pill-admin { background: #EFF6FF; color: #1D4ED8; }
    .pill-active { background: #F0FDF4; color: #166534; }
    .pill-inactive { background: #FEF2F2; color: #991B1B; }
    .pill-pending { background: #FEF3C7; color: #92400E; }
    .pill-rejected { background: #FEF2F2; color: #991B1B; }

    /* ----- Boutons ----- */
    .btn-accent {
        background: var(--accent, #1D9E75); color: white; border: none;
        padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: background 0.2s;
    }
    .btn-accent:hover { background: var(--accent-dark, #0F6E56); color: white; }

    /* ----- Liens rapides ----- */
    .quick-link {
        display: flex; align-items: center; gap: 14px; padding: 12px 14px; border-radius: 12px;
        border: 1px solid var(--border-color, #EAECF0);
        text-decoration: none; color: var(--text-primary, #1a1a2e);
        transition: background 0.2s, border-color 0.2s;
    }
    .quick-link:hover { background: var(--bg-hover, #F8F9FB); border-color: var(--accent, #1D9E75); }
    .quick-icon {
        width: 38px; height: 38px; border-radius: 10px; display: flex;
        align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3 fade-up">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:2px">
            Bonjour, {{ auth()->user()->prenom ?? auth()->user()->username }} 👋
        </h1>
        <p style="font-size:13px;color:var(--text-muted);margin:0">
            {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }} — Tableau de bord du magasin
        </p>
    </div>
    <a href="{{ route('admin.super.users.create') }}" class="btn-accent">
        <i class="ti ti-user-plus" style="font-size:15px"></i> Nouvel utilisateur
    </a>
</div>

{{-- ALERTES --}}
@if($pendingCategoriesCount > 0)
<div class="alert-banner fade-up" style="background:var(--accent-light);border-left:4px solid var(--accent);color:#92400E;">
    <i class="ti ti-clock-exclamation"></i>
    <div>
        <strong>{{ $pendingCategoriesCount }} catégorie(s) en attente</strong>
        <a href="{{ route('admin.super.categories.pending') }}" style="color:inherit;text-decoration:underline;">Approuver →</a>
    </div>
</div>
@endif
@if($facturesImpayees > 0)
<div class="alert-banner fade-up" style="background:#FEF2F2;border-left:4px solid #EF4444;color:#991B1B;">
    <i class="ti ti-alert-triangle"></i>
    <div>
        <strong>{{ number_format($facturesImpayees, 0, ',', ' ') }} FCFA d'impayés</strong>
        <a href="{{ route('admin.factures.index') }}" style="color:inherit;text-decoration:underline;">Voir les factures →</a>
    </div>
</div>
@endif

{{-- KPI PRINCIPAUX --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['label'=>'Chiffre d\'affaires','value'=>$chiffreAffaires,'bar'=>'#1D9E75','icon'=>'ti-currency-dollar','bg'=>'var(--accent-light)','color'=>'var(--accent)','sub'=>'Commandes livrées','link'=>'#'],
        ['label'=>'Commandes totales','value'=>$totalCommandes,'bar'=>'#1D4ED8','icon'=>'ti-shopping-cart','bg'=>'#EFF6FF','color'=>'#1D4ED8','sub'=>'Tous statuts confondus','link'=>'#'],
        ['label'=>'Factures impayées','value'=>$facturesImpayees,'bar'=>'#D97706','icon'=>'ti-file-invoice','bg'=>'#FEF3C7','color'=>'#D97706','sub'=>'À encaisser','link'=>'#'],
        ['label'=>'Bénéfice net','value'=>$beneficeNet,'bar'=>'#991B1B','icon'=>'ti-coin','bg'=>'#FEF2F2','color'=>'#991B1B','sub'=>'CA − impayés','link'=>'#'],
    ] as $kpi)
    <div class="col-xl-3 col-md-6 fade-up">
        <a href="{{ $kpi['link'] ?? '#' }}" class="kpi-card">
            <div class="kpi-accent-bar" style="background:{{ $kpi['bar'] ?? '#ccc' }};"></div>
            <div class="kpi-label">{{ $kpi['label'] ?? '' }}</div>
            <div class="kpi-value {{ ($kpi['sm'] ?? false) ? 'sm' : '' }}">
                {{ number_format($kpi['value'] ?? 0, 0, ',', ' ') }}
                @if($kpi['sm'] ?? false)
                <small style="font-size:13px;font-weight:500;color:var(--text-muted)">FCFA</small>
                @endif
            </div>
            <div class="kpi-footer">
                <span class="kpi-sub">{{ $kpi['sub'] ?? '' }}</span>
                <div class="kpi-icon" style="background:{{ $kpi['bg'] ?? '#ccc' }};color:{{ $kpi['color'] ?? '#333' }};">
                    <i class="ti {{ $kpi['icon'] ?? '' }}"></i>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- GRAPHIQUE --}}
<div class="row g-3 mb-4">
    <div class="col-12 fade-up">
        <div class="sa-card">
            <div class="sa-card-header">
                <div class="sa-card-title"><i class="ti ti-chart-line" style="color:var(--accent);"></i> Ventes mensuelles</div>
                <span class="sa-card-action">12 derniers mois</span>
            </div>
            <div class="p-3"><div id="salesChart" style="height:300px;"></div></div>
        </div>
    </div>
</div>

{{-- STATISTIQUES SECONDAIRES --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['label'=>'Utilisateurs','value'=>$totalUsers,'sub'=>$usersActifs.' actifs','icon'=>'ti-users','bg'=>'#EFF6FF','color'=>'#1D4ED8','link'=>route('admin.super.users.index')],
        ['label'=>'Catégories','value'=>$totalCategories,'sub'=>$pendingCategoriesCount.' en attente','icon'=>'ti-category','bg'=>'#FEF3C7','color'=>'#D97706','link'=>route('admin.super.categories.index')],
        ['label'=>'Produits','value'=>$totalProduits,'sub'=>'En catalogue','icon'=>'ti-package','bg'=>'#F0FDF4','color'=>'#166534','link'=>'#'],
        ['label'=>'Valeur du stock','value'=>$valeurStock,'sub'=>'Prix × quantité','sm'=>true,'icon'=>'ti-coin','bg'=>'#FEF2F2','color'=>'#991B1B','link'=>'#'],
    ] as $stat)
    <div class="col-xl-3 col-md-6 fade-up">
        <a href="{{ $stat['link'] ?? '#' }}" class="kpi-card">
            <div class="kpi-label">{{ $stat['label'] ?? '' }}</div>
            <div class="kpi-value {{ ($stat['sm'] ?? false) ? 'sm' : '' }}">
                {{ number_format($stat['value'] ?? 0, 0, ',', ' ') }}
                @if($stat['sm'] ?? false)
                <small style="font-size:13px;font-weight:500;color:var(--text-muted)">FCFA</small>
                @endif
            </div>
            <div class="kpi-footer">
                <span class="kpi-sub">{{ $stat['sub'] ?? '' }}</span>
                <div class="kpi-icon" style="background:{{ $stat['bg'] ?? '#ccc' }};color:{{ $stat['color'] ?? '#333' }};">
                    <i class="ti {{ $stat['icon'] ?? '' }}"></i>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- SURVEILLANCE --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7 fade-up">
        <div class="sa-card">
            <div class="sa-card-header">
                <div class="sa-card-title"><i class="ti ti-user-check" style="color:#1D4ED8;"></i> Performance des gestionnaires</div>
                <a href="{{ route('admin.super.users.index') }}" class="sa-card-action">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="sa-table">
                    <thead><tr><th>Admin</th><th>Produits</th><th>Commandes</th><th>CA généré</th></tr></thead>
                    <tbody>
                        @forelse($adminsPerformance as $admin)
                        <tr>
                            <td style="font-weight:600;">{{ $admin->prenom }} {{ $admin->nom }} <br><small style="color:var(--text-muted)">{{ $admin->username }}</small></td>
                            <td><span class="pill pill-admin">{{ $admin->produits_count }}</span></td>
                            <td>{{ $admin->commandes_count }}</td>
                            <td>{{ number_format($admin->ca_genere, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">Aucun gestionnaire</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5 fade-up">
        <div class="sa-card">
            <div class="sa-card-header">
                <div class="sa-card-title"><i class="ti ti-package-off" style="color:#991B1B;"></i> Produits en faible stock</div>
                <a href="#" class="sa-card-action">Gérer</a>
            </div>
            <div class="table-responsive">
                <table class="sa-table">
                    <thead><tr><th>Produit</th><th>Stock</th></tr></thead>
                    <tbody>
                        @forelse($produitsFaibleStock as $p)
                        <tr>
                            <td style="font-weight:600;">{{ $p->nomp }}</td>
                            <td><span class="pill pill-inactive">{{ $p->quantite }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center py-3 text-muted">Tous les stocks sont bons ✅</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ACTIONS RAPIDES --}}
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="sa-card">
            <div class="sa-card-header">
                <div class="sa-card-title"><i class="ti ti-zap" style="color:var(--accent);"></i> Actions rapides</div>
            </div>
            <div class="p-3 d-flex flex-wrap gap-3">
                <a href="{{ route('admin.super.users.create') }}" class="quick-link">
                    <div class="quick-icon" style="background:#EFF6FF;color:#1D4ED8;"><i class="ti ti-user-plus"></i></div>
                    <div>
                        <div style="font-weight:600;">Créer un utilisateur</div>
                        <div style="font-size:11px;color:var(--text-muted)">Admin ou super admin</div>
                    </div>
                    <i class="ti ti-chevron-right ms-auto" style="color:var(--text-muted)"></i>
                </a>
                <a href="{{ route('admin.super.categories.create') }}" class="quick-link">
                    <div class="quick-icon" style="background:#F0FDF4;color:#166534;"><i class="ti ti-folder-plus"></i></div>
                    <div>
                        <div style="font-weight:600;">Nouvelle catégorie</div>
                        <div style="font-size:11px;color:var(--text-muted)">Catégorie de produits</div>
                    </div>
                    <i class="ti ti-chevron-right ms-auto" style="color:var(--text-muted)"></i>
                </a>
                <a href="{{ route('admin.super.categories.pending') }}" class="quick-link">
                    <div class="quick-icon" style="background:#FEF3C7;color:#D97706;"><i class="ti ti-clock-check"></i></div>
                    <div>
                        <div style="font-weight:600;">Valider les catégories</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $pendingCategoriesCount }} en attente</div>
                    </div>
                    <i class="ti ti-chevron-right ms-auto" style="color:var(--text-muted)"></i>
                </a>
                <a href="{{ route('admin.super.users.index') }}" class="quick-link">
                    <div class="quick-icon" style="background:#FEF2F2;color:#991B1B;"><i class="ti ti-users"></i></div>
                    <div>
                        <div style="font-weight:600;">Gérer les utilisateurs</div>
                        <div style="font-size:11px;color:var(--text-muted)">Activer, modifier, supprimer</div>
                    </div>
                    <i class="ti ti-chevron-right ms-auto" style="color:var(--text-muted)"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var ventes = @json($ventesMensuelles);
    var categories = ventes.map(function(v){ return v.mois; });
    var seriesData = ventes.map(function(v){ return v.total; });

    var options = {
        chart: { type: 'area', height: 300, toolbar: { show: false }, zoom: { enabled: false } },
        series: [{ name: 'Ventes (FCFA)', data: seriesData }],
        xaxis: { categories: categories, labels: { style: { colors: '#8a8fa8', fontSize: '11px' } } },
        yaxis: { labels: { formatter: function(val){ return val.toLocaleString('fr-FR') + ' FCFA'; }, style: { colors: '#8a8fa8' } } },
        colors: ['#1D4ED8'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
        stroke: { curve: 'smooth', width: 2 },
        dataLabels: { enabled: false },
        tooltip: { theme: 'dark', y: { formatter: function(val){ return val.toLocaleString('fr-FR') + ' FCFA'; } } }
    };
    var chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
</script>
@endpush
