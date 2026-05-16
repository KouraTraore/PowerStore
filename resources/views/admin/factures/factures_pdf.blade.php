<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->nomf }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; line-height: 1.4; }
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

    <div class="client-info">
        <h4>Client</h4>
        @if($facture->client)
            <p><strong>{{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? '' }}</strong><br>
            Tél : {{ $facture->client->tel ?? 'Non renseigné' }}<br>
            Email : {{ $facture->client->email ?? 'Non renseigné' }}<br>
            Adresse : {{ $facture->client->adresse ?? 'Non renseignée' }}</p>
        @else
            <p>Client non renseigné</p>
        @endif
    </div>

    <h4>Détail de la facture</h4>
    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th class="text-end">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Montant total</td>
                <td class="text-end">{{ number_format($facture->montant_total, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>Montant payé</td>
                <td class="text-end">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>Reste à payer</td>
                <td class="text-end">{{ number_format($facture->reste_a_payer, 0, ',', ' ') }}</td>
            </tr>
            @if($facture->mode_paiement)
            <tr>
                <td>Mode de paiement</td>
                <td class="text-end">{{ $facture->mode_paiement }}</td>
            </tr>
            @endif
            @if($facture->reference_paiement)
            <tr>
                <td>Référence paiement</td>
                <td class="text-end">{{ $facture->reference_paiement }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="total">
        TOTAL À PAYER : {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
    </div>

    <div class="footer">
        <p>Merci de votre confiance. Règlement à effectuer sous 30 jours.</p>
        <p>Ce document fait office de facture officielle.</p>
    </div>
</body>
</html>