<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande #{{ $commande->id }} - POWERSTORE</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #667eea; padding-bottom: 15px; }
        .logo { font-size: 28px; font-weight: bold; color: #667eea; }
        .company-info { margin-top: 5px; font-size: 11px; color: #666; }
        .commande-info { margin-bottom: 30px; }
        .client-info { margin-bottom: 30px; background: #f8f9fa; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        .text-end { text-align: right; }
        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 10px; border-top: 2px solid #667eea; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #999; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; background: #f0f0f0; }
        .badge-success { background: #10b981; color: white; }
        .badge-warning { background: #f59e0b; color: white; }
        .badge-danger { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">POWERSTOCK</div>
        <div class="company-info">
            Bamako - Mali | Tél: 77-90-34-44 | Email: contact@powerstock.com
        </div>
    </div>

    <div class="commande-info">
        <h2>BON DE COMMANDE N° {{ $commande->id }}</h2>
        <p>Date : {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</p>
        <p>Statut : 
            @if($commande->statut == 'livree')
                <span class="badge badge-success">Livrée</span>
            @elseif($commande->statut == 'en_attente')
                <span class="badge badge-warning">En attente</span>
            @else
                <span class="badge badge-danger">Annulée</span>
            @endif
        </p>
    </div>

    <div class="client-info">
        <h4>Client</h4>
        <p><strong>{{ $commande->client->prenom ?? '' }} {{ $commande->client->nomc ?? '' }}</strong><br>
        Tél : {{ $commande->client->tel ?? 'Non renseigné' }}<br>
        Email : {{ $commande->client->email ?? 'Non renseigné' }}<br>
        Adresse : {{ $commande->client->adresse ?? 'Non renseignée' }}</p>
    </div>

    <h4>Produits commandés</h4>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Quantité</th>
                <th class="text-end">Total (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->details as $detail)
            <tr>
                <td>{{ $detail->produit->nomp ?? 'Produit' }}</td>
                <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }}</td>
                <td>{{ $detail->quantite }}</td>
                <td class="text-end">{{ number_format($detail->prix_unitaire * $detail->quantite, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="total">
        Montant total à payer : {{ number_format($commande->total_ttc, 0, ',', ' ') }} FCFA
    </div>

    <div class="footer">
        <p>Merci de votre confiance.</p>
        <p>Ce document fait office de bon de commande officiel.</p>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>