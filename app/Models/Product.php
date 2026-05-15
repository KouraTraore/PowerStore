<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nomp',
        'prix',
        'quantite',
        'description',
        'image',
        'categorie_id',
        'created_by'
    ];

    protected $casts = [
        'prix' => 'integer',
        'quantite' => 'integer',
        'categorie_id' => 'integer',
        'created_by' => 'integer',
    ];

    // Relation avec la catégorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    // Relation avec l'utilisateur
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation avec les détails de commande
    public function detailsCommande()
    {
        return $this->hasMany(DetailCommande::class, 'produit_id');
    }

    // Prix formaté
    public function getFormattedPriceAttribute()
    {
        return number_format($this->prix, 0, ',', ' ') . ' FCFA';
    }

    // Badge stock
    public function getStockBadgeAttribute()
    {
        if ($this->quantite <= 0) {
            return '<span class="badge bg-danger">Rupture</span>';
        } elseif ($this->quantite < 5) {
            return '<span class="badge bg-warning text-dark">Stock faible (' . $this->quantite . ')</span>';
        }

        return '<span class="badge bg-success">Stock (' . $this->quantite . ')</span>';
    }

    // Valeur totale
    public function getTotalValueAttribute()
    {
        return $this->prix * $this->quantite;
    }

    // Scope stock faible
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('quantite', '<', $threshold);
    }
}