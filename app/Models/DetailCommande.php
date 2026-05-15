<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailCommande extends Model
{
    protected $table = 'detail_commande';
    public $timestamps = false; // pas de created_at/updated_at dans cette table

    protected $fillable = [
        'commande_id', 'produit_id', 'quantite', 'prix_unitaire'
    ];

    // Relation : un détail appartient à une commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    // Relation : un détail appartient à un produit
    public function produit()
    {
        return $this->belongsTo(Product::class, 'produit_id');
    }

    // Accessoire pour le total de la ligne
    public function getTotalLigneAttribute()
    {
        return $this->quantite * $this->prix_unitaire;
    }
}