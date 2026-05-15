<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'factures';
    public $timestamps = false;

    protected $fillable = [
        'nomf', 'client_id', 'datef', 'etatf',
        'montant_total', 'statut', 'mode_paiement', 'date_paiement',
        'reference_paiement', 'montant_paye', 'reste_a_payer', 'email_envoye', 'date_envoi_email'
    ];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Relation avec les commandes (une facture peut avoir plusieurs commandes)
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'facture_id');
    }
}