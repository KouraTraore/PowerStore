<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits'; // nom explicite car non-standard

    protected $fillable = [
        'nomp', 'prix', 'quantite', 'description',
        'image', 'created_by', 'categorie_id',
    ];

    // ── Relations ──

    // La catégorie du produit
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    // Qui a créé ce produit
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accesseur : valeur totale en stock ──
    // $produit->stock_value  →  prix × quantité
    public function getStockValueAttribute(): int
    {
        return $this->prix * $this->quantite;
    }
}
