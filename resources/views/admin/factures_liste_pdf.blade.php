<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Factures</title>
    <style>
        @page { 
            margin: 20px;
            size: landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            background: white;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .info {
            margin-bottom: 20px;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .total-general {
            margin-top: 20px;
            padding: 15px;
            background: #f5f5f5;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
        .statut-paye {
            color: #10b981;
            font-weight: bold;
        }
        .statut-partiel {
            color: #f59e0b;
            font-weight: bold;
        }
        .statut-non-paye {
            color: #ef4444;
            font-weight: bold;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">POWERSTOCK</div>
        <p>Bamako - Mali | Tél: 77-90-34-44</p>
        <p>Email: contact@powerstock.com</p>
    </div>

    <div class="title">
        📋 LISTE COMPLÈTE DES FACTURES
    </div>

    <div class="info">
        <strong>Date d'exportation:</strong> {{ $stats['date'] }}<br>
        <strong>Nombre total de factures:</strong> {{ $factures->count() }}<br>
        <strong>Montant total général:</strong> {{ number_format($stats['total'], 0, ',', ' ') }} FCFA
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Date</th>
                <th>Client</th>
                <th>Téléphone</th>
                <th>Montant Total</th>
                <th>Montant Payé</th>
                <th>Reste à Payer</th>
                <th>% Payé</th>
                <th>Statut</th>
                <th>Mode Paiement</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0;
                $totalPayeGeneral = 0;
                $totalResteGeneral = 0;
            @endphp
            
            @foreach($factures as $facture)
            @php
                $totalGeneral += $facture->montant_total;
                $totalPayeGeneral += $facture->montant_paye;
                $totalResteGeneral += $facture->reste_a_payer;
                $pourcentage = $facture->montant_total > 0 ? round(($facture->montant_paye / $facture->montant_total) * 100) : 0;
            @endphp
            <tr>
                <td><strong>#{{ $facture->id }}</strong></strong></td>
                <td>{{ $facture->created_at->format('d/m/Y') }}</strong></td>
                <td>
                    {{ $facture->client->prenom ?? '' }} {{ $facture->client->nomc ?? 'Client' }}
                    @if($facture->client && $facture->client->email)
                        <br><small>{{ $facture->client->email }}</small>
                    @endif
                </strong></td>
                <td>{{ $facture->client->tel ?? '-' }}</strong></td>
                <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></strong></td>
                <td>{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</strong></td>
                <td><strong>{{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA</strong></strong></td>
                <td>{{ $pourcentage }}%</strong></td>
                <td>
                    @if($facture->statut == 'Payé')
                        <span class="statut-paye">✅ Payé</span>
                    @elseif($facture->statut == 'Partiel')
                        <span class="statut-partiel">⚠️ Partiel</span>
                    @else
                        <span class="statut-non-paye">❌ Non payé</span>
                    @endif
                 </strong></td>
                <td>
                    @if($facture->mode_paiement)
                        @if($facture->mode_paiement == 'Espèces') 💰
                        @elseif($facture->mode_paiement == 'Carte') 💳
                        @elseif($facture->mode_paiement == 'Mobile Money') 📱
                        @elseif($facture->mode_paiement == 'Virement') 🏦
                        @endif
                        {{ $facture->mode_paiement }}
                    @else
                        -
                    @endif
                 </strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #667eea; color: white; font-weight: bold;">
                <td colspan="4" style="text-align: right;"><strong>TOTAUX:</strong></td>
                <td><strong>{{ number_format($totalGeneral, 0, ',', ' ') }} FCFA</strong></td>
                <td><strong>{{ number_format($totalPayeGeneral, 0, ',', ' ') }} FCFA</strong></td>
                <td><strong>{{ number_format($totalResteGeneral, 0, ',', ' ') }} FCFA</strong></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    @php
        $tauxRecouvrement = $totalGeneral > 0 ? round(($totalPayeGeneral / $totalGeneral) * 100) : 0;
    @endphp

    <div class="total-general">
        <table width="100%" style="border: none;">
            <tr>
                <td width="60%" style="border: none;">
                    <strong>📊 RÉCAPITULATIF GÉNÉRAL</strong><br>
                    • Total factures: {{ $factures->count() }}<br>
                    • Factures payées: {{ $factures->where('statut', 'Payé')->count() }}<br>
                    • Factures partielles: {{ $factures->where('statut', 'Partiel')->count() }}<br>
                    • Factures impayées: {{ $factures->where('statut', 'Non payé')->count() }}
                </td>
                <td width="40%" style="border: none; text-align: right;">
                    <strong>💰 RÉCAPITULATIF FINANCIER</strong><br>
                    Montant total: <strong>{{ number_format($totalGeneral, 0, ',', ' ') }} FCFA</strong><br>
                    Montant payé: <strong>{{ number_format($totalPayeGeneral, 0, ',', ' ') }} FCFA</strong><br>
                    Reste à payer: <strong>{{ number_format($totalResteGeneral, 0, ',', ' ') }} FCFA</strong><br>
                    Taux de recouvrement: <strong>{{ $tauxRecouvrement }}%</strong>
                 </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Document généré automatiquement par POWERSTOCK - {{ $stats['date'] }}</p>
        <p>* Ce document fait foi pour la gestion des factures et des paiements</p>
    </div>
</body>
</html>