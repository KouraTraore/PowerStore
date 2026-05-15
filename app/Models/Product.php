<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

<<<<<<< HEAD
    // Le nom de la table dans la base de données
    protected $table = 'produits';

    // Les champs qu'on peut remplir
    protected $fillable = [
        'nomp',
        'prix',
        'quantite',
        'description',
        'image',
        'created_by',
        'categorie_id'
    ];

    // Désactiver les timestamps si ta table n'a pas created_at/updated_at
    public $timestamps = false;

    // Relation avec la catégorie
    public function categorie()
=======
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

    // Relation avec la catégorie (modèle de ton collègue)
    public function category()
>>>>>>> origin/mousstafa_features
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

<<<<<<< HEAD
    // Relation avec l'utilisateur qui a créé le produit
    public function createur()
=======
    // Relation avec l'utilisateur
    public function creator()
>>>>>>> origin/mousstafa_features
    {
        return $this->belongsTo(User::class, 'created_by');
    }

<<<<<<< HEAD
    // ✅ AJOUTE CETTE RELATION (pour lier aux détails de commande)
    public function detailsCommande()
    {
        return $this->hasMany(DetailCommande::class, 'produit_id');
    }
}
=======
    // Accesseurs
    public function getFormattedPriceAttribute()
    {
        return number_format($this->prix, 0, ',', ' ') . ' FCFA';
    }

    public function getStockBadgeAttribute()
    {
        if ($this->quantite <= 0) {
            return '<span class="badge bg-danger">Rupture</span>';
        } elseif ($this->quantite < 5) {
            return '<span class="badge bg-warning text-dark">Stock faible (' . $this->quantite . ')</span>';
        }
        return '<span class="badge bg-success">Stock (' . $this->quantite . ')</span>';
    }

    public function getTotalValueAttribute()
    {
        return $this->prix * $this->quantite;
    }

    // Scopes
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('quantite', '<', $threshold);
    }
} 
>>>>>>> origin/mousstafa_features
