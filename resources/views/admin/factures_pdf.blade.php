<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $facture->id }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        .facture-info {
            margin-bottom: 30px;
        }
        .facture-info table {
            width: 100%;
        }
        .client-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            padding: 15px;
            background: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
        .statut {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .statut-paye { background: #10b981; color: white; }
        .statut-partiel { background: #f59e0b; color: white; }
        .statut-non-paye { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">POWERSTOCK</div>
        <p>Bamako - Mali | Tél: 77-90-34-44</p>
        <p>Email: contact@powerstock.com</p>
    </div>

    <div class="facture-info">
        <table>
            <tr>
                <td width="50%"><strong>FACTURE N°:</strong> {{ $facture->id }}</td>
                <td width="50%"><strong>Date:</strong> {{ $facture->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Statut:</strong> 
                    <span class="statut statut-{{ strtolower(str_replace(' ', '-', $facture->statut)) }}">
                        {{ $facture->statut }}
                    </span>
                 </td>
                <td><strong>Lieu:</strong> Bamako</td>
            </tr>
        </table>
    </div>

    <div class="client-info">
        <h3>📋 Informations client</h3>
        <p><strong>Nom:</strong> {{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? 'Client N°'.$facture->client_id }}</p>
        <p><strong>Adresse:</strong> {{ $facture->client->adresse ?? 'Non renseignée' }}</p>
        <p><strong>Téléphone:</strong> {{ $facture->client->tel ?? 'Non renseigné' }}</p>
        @if($facture->client && $facture->client->email)
        <p><strong>Email:</strong> {{ $facture->client->email }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Désignation</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Facture POWERSTOCK</td>
                <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                <td>1</td>
                <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <table width="100%">
            <tr>
                <td width="70%"></td>
                <td width="30%">
                    <strong>Sous-total:</strong> {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA<br>
                    @if($facture->montant_paye > 0)
                    <strong>Montant payé:</strong> {{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA<br>
                    <strong>Reste à payer:</strong> {{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA<br>
                    @endif
                    <hr>
                    <strong style="font-size: 20px;">TOTAL: {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong>
                </td>
            </tr>
        </table>
    </div>

    @if($facture->mode_paiement)
    <div style="margin-top: 20px; padding: 10px; background: #e8f4fd; border-radius: 5px;">
        <strong>💳 Informations de paiement:</strong><br>
        Mode: {{ $facture->mode_paiement }}<br>
        @if($facture->date_paiement) Date: {{ \Carbon\Carbon::parse($facture->date_paiement)->format('d/m/Y') }}<br> @endif
        @if($facture->reference_paiement) Référence: {{ $facture->reference_paiement }}<br> @endif
    </div>
    @endif

    <div class="footer">
        <p>Merci de votre confiance !</p>
        <p>* Facture générée automatiquement par POWERSTOCK</p>
    </div>
</body>
</html>