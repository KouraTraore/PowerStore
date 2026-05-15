<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->nomf }} - POWERSTOCK</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #667eea; padding-bottom: 15px; }
        .logo { font-size: 28px; font-weight: bold; color: #667eea; }
        .company-info { margin-top: 5px; font-size: 11px; color: #666; }
        .facture-info { margin-bottom: 30px; }
        .client-info { margin-bottom: 30px; background: #f8f9fa; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        .text-end { text-align: right; }
        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 10px; border-top: 2px solid #667eea; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #999; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #10b981; color: white; }
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

    <div class="facture-info">
        <h2>FACTURE {{ $facture->nomf }}</h2>
        <p>Date d'émission : {{ \Carbon\Carbon::parse($facture->datef)->format('d/m/Y H:i') }}</p>
        <p>Statut : 
            @if($facture->etatf == 1)
                <span class="badge badge-success">Payée</span>
            @else
                <span class="badge badge-danger">Non payée</span>
            @endif
        </p>
    </div>

    @php $client = $facture->commandes->first()?->client; @endphp
    <div class="client-info">
        <h4>Client</h4>
        @if($client)
            <p><strong>{{ $client->prenom }} {{ $client->nomc }}</strong><br>
            Tél : {{ $client->tel }}<br>
            Email : {{ $client->email }}<br>
            Adresse : {{ $client->adresse }}</p>
        @else
            <p class="text-muted">Aucun client associé.</p>
        @endif
    </div>

    <h4>Détail des produits</h4>
    <table>
        <thead>
            <tr>
                <th>Commande</th>
                <th>Produit</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Quantité</th>
                <th class="text-end">Total (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facture->commandes as $commande)
                @foreach($commande->details as $detail)
                <tr>
                    <td><a href="{{ route('admin.commandes.show', $commande->id) }}">#{{ $commande->id }}</a></td>
                    <td>{{ $detail->produit->nomp ?? 'N/A' }}</td>
                    <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }}</td>
                    <td>{{ $detail->quantite }}</td>
                    <td class="text-end">{{ number_format($detail->prix_unitaire * $detail->quantite, 0, ',', ' ') }}</td>
                </tr>
                @endforeach
            @empty
                <tr><td colspan="5" class="text-center py-4">Aucune commande liée.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="total">
        Montant à payer : {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
    </div>

    <div class="footer">
        <p>Merci de votre confiance. Règlement à effectuer sous 30 jours.</p>
        <p>Ce document fait office de facture officielle.</p>
    </div>

    <script>window.onload = function() { window.print(); };</script>
</body>
</html>