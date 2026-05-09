<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture POWERSTOCK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .facture-details {
            margin: 20px 0;
        }
        .facture-details h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #667eea;
            color: white;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 8px;
        }
        .statut {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .statut-paye { background: #10b981; color: white; }
        .statut-partiel { background: #f59e0b; color: white; }
        .statut-non-paye { background: #ef4444; color: white; }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏪 POWERSTOCK</h1>
            <p>Bamako - Mali | Tél: 77-90-34-44</p>
            <p>Email: contact@powerstock.com</p>
        </div>

        <div class="content">
            <div class="info">
                <p><strong>🔖 FACTURE N°:</strong> {{ $facture->id }}</p>
                <p><strong>📅 Date:</strong> {{ $facture->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>📍 Lieu:</strong> Bamako</p>
                <p>
                    <strong>📊 Statut:</strong> 
                    <span class="statut statut-{{ strtolower(str_replace(' ', '-', $facture->statut)) }}">
                        @if($facture->statut == 'Payé') ✅ Payé
                        @elseif($facture->statut == 'Partiel') ⏳ Partiel
                        @else ❌ Non payé
                        @endif
                    </span>
                </p>
            </div>

            <div class="facture-details">
                <h3>👤 Informations client</h3>
                <div class="info">
                    <p><strong>Nom:</strong> {{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? 'Client N°'.$facture->client_id }}</p>
                    <p><strong>Adresse:</strong> {{ $facture->client->adresse ?? 'Non renseignée' }}</p>
                    <p><strong>Téléphone:</strong> {{ $facture->client->tel ?? 'Non renseigné' }}</p>
                    @if($facture->client && $facture->client->email)
                    <p><strong>Email:</strong> {{ $facture->client->email }}</p>
                    @endif
                </div>
            </div>

            <h3>📋 Détails de la facture</h3>
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Facture POWERSTOCK</strong></td>
                        <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                        <td>1</strong></td>
                        <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="total">
                <strong>💰 TOTAL À PAYER : {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong><br>
                @if($facture->montant_paye > 0)
                <small>Déjà payé : {{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</small><br>
                <small>Reste à payer : {{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA</small>
                @endif
            </div>

            @if($facture->mode_paiement)
            <div class="info" style="margin-top: 20px;">
                <p><strong>💳 Informations de paiement:</strong></p>
                <p>Mode: 
                    @if($facture->mode_paiement == 'Espèces') 💰 Espèces
                    @elseif($facture->mode_paiement == 'Carte') 💳 Carte bancaire
                    @elseif($facture->mode_paiement == 'Mobile Money') 📱 Mobile Money
                    @elseif($facture->mode_paiement == 'Virement') 🏦 Virement bancaire
                    @endif
                </p>
                @if($facture->date_paiement)
                <p>Date de paiement: {{ \Carbon\Carbon::parse($facture->date_paiement)->format('d/m/Y') }}</p>
                @endif
                @if($facture->reference_paiement)
                <p>Référence: {{ $facture->reference_paiement }}</p>
                @endif
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('admin.factures.pdf', $facture->id) }}" class="btn">
                    📄 Télécharger la facture en PDF
                </a>
            </div>
        </div>

        <div class="footer">
            <p>📧 Cet email a été envoyé automatiquement par POWERSTOCK.</p>
            <p>Merci de votre confiance !</p>
            <p>© {{ date('Y') }} POWERSTOCK - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>