<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commandes';
    public $timestamps = false;

    protected $fillable = [
        'client_id', 'created_by', 'produit_id', 'facture_id',
        'date_commande', 'statut', 'total_ttc'
    ];

    // Relation : une commande appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Relation : une commande appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation : une commande a plusieurs lignes de détail
    public function details()
    {
        return $this->hasMany(DetailCommande::class, 'commande_id');
    }

    // Relation : une commande peut avoir une facture
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'facture_id');
    }

    // Accessoire pour afficher le statut en français
    public function getStatutLabelAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'livree'     => 'Livrée',
            'annulee'    => 'Annulée',
            default      => $this->statut,
        };
    }

    // Accessoire pour le badge Bootstrap
    public function getStatutBadgeAttribute()
    {
        $class = match($this->statut) {
            'en_attente' => 'warning',
            'livree'     => 'success',
            'annulee'    => 'danger',
            default      => 'secondary',
        };
        return "<span class='badge bg-{$class}'>{$this->statut_label}</span>";
    }
}
