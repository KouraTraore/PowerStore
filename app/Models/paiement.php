<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';
    protected $fillable = [
        'facture_id', 'montant', 'reference', 'date_paiement', 'mode'
    ];

    // Relation : un paiement appartient à une facture
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    // Accessoire pour le mode de paiement en français
    public function getModeLabelAttribute()
    {
        return match($this->mode) {
            'especes'      => 'Espèces',
            'carte'        => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            'virement'     => 'Virement',
            default        => $this->mode,
        };
    }
}