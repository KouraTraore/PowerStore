<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Impression - Facture {{ $facture->nomf }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .logo { font-size: 24px; font-weight: bold; color: #667eea; }
        .facture-info, .client-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .text-end { text-align: right; }
        .total { font-weight: bold; text-align: right; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #666; }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">POWERSTORE</div>
        <div>Bamako - Mali | Tél: 77-90-34-44 | contact@powerstore.com</div>
    </div>

    <div class="facture-info">
        <h2>FACTURE {{ $facture->nomf }}</h2>
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($facture->datef)->format('d/m/Y H:i') }}</p>
        <p><strong>Statut :</strong> 
            @if($facture->etatf == 1) Payée @else Non payée @endif
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
        <p>Merci de votre confiance. Ce document fait office de facture officielle.</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>