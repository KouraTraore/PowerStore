@extends('layouts.admin')

@section('title', 'Clients')

@push('styles')
<style>
    :root { --primary: #E66239; --border: #e2e8f0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
    }
    .stat-card .label {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .filter-bar {
        background: white;
        border-radius: 20px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border: 1px solid var(--border);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: space-between;
        align-items: center;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 40px;
        padding: 4px 16px;
        gap: 8px;
        flex: 1;
        min-width: 200px;
    }
    .search-box input {
        border: none;
        background: transparent;
        padding: 8px;
        width: 100%;
        outline: none;
    }
    .order-buttons {
        display: flex;
        gap: 8px;
    }
    .btn-order {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        transition: all 0.2s;
        text-decoration: none;
        color: #1e293b;
    }
    .btn-order.active,
    .btn-order:hover {
        background: var(--primary);
        color: white;
    }
    .btn-export {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        transition: all 0.2s;
        color: #1e293b;
        text-decoration: none;
    }
    .btn-export:hover {
        background: var(--primary);
        color: white;
    }

    .table-container {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow-x: auto;
    }
    table {
        width: 100%;
        min-width: 700px;
        margin-bottom: 0;
    }
    th {
        background: #f8fafc;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        white-space: nowrap;
    }
    .client-row:hover {
        background: #fef4f0;
    }
    .badge-client {
        background: #e2e8f0;
        color: #1e293b;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--border);
        cursor: pointer;
        margin: 0 2px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }
    .action-btn.view:hover { background: var(--primary); color: white; }
    .action-btn.edit:hover { background: #f59e0b; color: white; }
    .action-btn.delete:hover { background: #ef4444; color: white; }


    .btn-primary-custom {
        background: var(--primary);
        border: none;
        padding: 8px 20px;
        border-radius: 12px;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-primary-custom:hover {
        background: #d5542e;
        transform: translateY(-2px);
        color: white;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .filter-bar { flex-direction: column; align-items: stretch; }
        .order-buttons { justify-content: flex-end; }
    }
    .pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.page-item {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: white;
    border: 1px solid var(--border);
    color: #1e293b;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
    cursor: pointer;
}
.page-item:hover {
    border-color: var(--primary);
    color: var(--primary);
}
.page-item.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.page-item.disabled {
    opacity: 0.5;
    pointer-events: none;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fs-3 fw-bold mb-1">
                <i class="fa-solid fa-users me-2" style="color: var(--primary);"></i>Clients
            </h1>
            <p class="text-secondary mb-0 small">Gérez votre portefeuille clients</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn-primary-custom">
            <i class="fa-solid fa-plus"></i> Nouveau client
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="value">{{ $totalClients }}</div>
            <div class="label">Total clients</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $clientsActifs }}</div>
            <div class="label">Clients actifs</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ number_format($caTotal, 0, ',', ' ') }} FCFA</div>
            <div class="label">Chiffre d'affaires</div>
        </div>
    </div>

    <!-- Barre de filtres -->
    <div class="filter-bar">
        <form method="GET" class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher (nom, téléphone, email)...">
            <input type="hidden" name="order" value="{{ $order }}">
        </form>
        <div class="order-buttons">
            <a href="?order=asc&search={{ $search }}" class="btn-order {{ $order == 'asc' ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-up-a-z"></i> A → Z
            </a>
            <a href="?order=desc&search={{ $search }}" class="btn-order {{ $order == 'desc' ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-down-z-a"></i> Z → A
            </a>
            <a href="#" class="btn-export" id="exportExcelBtn" title="Export Excel">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="#" class="btn-export" id="exportPdfBtn" title="Export PDF">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Tableau -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Commandes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr class="client-row">
                    <td><strong>{{ $client->prenom }} {{ $client->nomc }}</strong></td>
                    <td>{{ $client->tel ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $client->email ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 180px;">{{ $client->adresse ?? '-' }}</td>
                    <td><span class="badge-client">{{ $client->nb_commandes }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.clients.show', $client) }}" class="action-btn view" title="Voir">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.clients.edit', $client) }}" class="action-btn edit" title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce client ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5">Aucun client trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

<!-- Pagination personnalisée -->
@if ($clients->hasPages())
<div class="pagination justify-content-end mt-3">
    {{-- Flèche précédente --}}
    @if ($clients->onFirstPage())
        <span class="page-item disabled"><i class="fa-solid fa-chevron-left"></i></span>
    @else
        <a class="page-item" href="{{ $clients->previousPageUrl() }}&order={{ $order }}&search={{ $search }}"><i class="fa-solid fa-chevron-left"></i></a>
    @endif

    {{-- Numéros de page --}}
    @foreach ($clients->getUrlRange(1, $clients->lastPage()) as $page => $url)
        @if ($page == $clients->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&order={{ $order }}&search={{ $search }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Flèche suivante --}}
    @if ($clients->hasMorePages())
        <a class="page-item" href="{{ $clients->nextPageUrl() }}&order={{ $order }}&search={{ $search }}"><i class="fa-solid fa-chevron-right"></i></a>
    @else
        <span class="page-item disabled"><i class="fa-solid fa-chevron-right"></i></span>
    @endif
</div>
@endif
@endsection

@push('scripts')
<script>
    // Export Excel
    document.getElementById('exportExcelBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        let clients = JSON.parse('@json($clients->items())');
        let rows = [];
        rows.push(['Client', 'Téléphone', 'Email', 'Adresse', 'Commandes']);
        clients.forEach(c => {
            let fullName = c.prenom + (c.nomc ? ' ' + c.nomc : '');
            rows.push([fullName, c.tel || '', c.email || '', c.adresse || '', c.nb_commandes || 0]);
        });
        let csvContent = rows.map(row =>
            row.map(cell => {
                let escaped = String(cell).replace(/"/g, '""');
                if (escaped.includes(',') || escaped.includes('\n') || escaped.includes('"')) return `"${escaped}"`;
                return escaped;
            }).join(';')
        ).join('\n');
        let blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `clients_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Export PDF (impression)
    document.getElementById('exportPdfBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        let clients = JSON.parse(document.getElementById('clients-data').dataset.clients);
        let printContent = `
        <!DOCTYPE html><html><head><meta charset="UTF-8"><title>Liste des clients</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #E66239; }
            .header h1 { color: #E66239; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 10px; }
            th { background-color: #f5f5f5; }
            .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        </style></head><body>
        <div class="header"><h1>POWERSTOCK</h1><p>Bamako - Mali | Tel: 77-90-34-44</p><h3>Liste des clients</h3><p>Date : ${new Date().toLocaleDateString('fr-FR')}</p></div>
        <table><thead><tr><th>Client</th><th>Téléphone</th><th>Email</th><th>Adresse</th><th>Commandes</th></tr></thead><tbody>`;
        clients.forEach(c => {
            let fullName = c.prenom + (c.nomc ? ' ' + c.nomc : '');
            printContent += `<tr><td>${fullName}</td><td>${c.tel || '-'}</td><td>${c.email || '-'}</td><td>${c.adresse || '-'}</td><td style="text-align: center">${c.nb_commandes || 0}</td></tr>`;
        });
        printContent += `</tbody></table><div class="footer"><p>PowerStock - Système de facturation</p><p>© ${new Date().getFullYear()} Tous droits réservés</p></div></body></html>`;
        let win = window.open('', '_blank');
        win.document.write(printContent);
        win.document.close();
        win.onload = function() { win.print(); };
    });
</script>
@endpush
