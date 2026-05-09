<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'client_id',
        'commande_id',
        'montant_total',
        'statut',
        'mode_paiement',
        'date_paiement',
        'reference_paiement',
        'montant_paye',
        'reste_a_payer',
        'email_envoye',
        'date_envoi_email'
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'date_envoi_email' => 'datetime',
        'montant_total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste_a_payer' => 'decimal:2'
    ];

    // Relation avec Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec Commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Accesseur pour le reste à payer
    public function getResteAPayerAttribute()
    {
        return $this->montant_total - $this->montant_paye;
    }

    // Accesseur pour le pourcentage payé
    public function getPourcentagePayeAttribute()
    {
        if ($this->montant_total == 0) return 0;
        return round(($this->montant_paye / $this->montant_total) * 100);
    }

    // Mutateur pour mettre à jour automatiquement
    public function setMontantPayeAttribute($value)
    {
        $this->attributes['montant_paye'] = $value;
        $this->attributes['reste_a_payer'] = $this->montant_total - $value;
        
        if ($value >= $this->montant_total) {
            $this->attributes['statut'] = 'Payé';
        } elseif ($value > 0) {
            $this->attributes['statut'] = 'Partiel';
        } else {
            $this->attributes['statut'] = 'Non payé';
        }
    }
}