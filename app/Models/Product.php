<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    // Relation avec l'utilisateur qui a créé le produit
    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ AJOUTE CETTE RELATION (pour lier aux détails de commande)
    public function detailsCommande()
    {
        return $this->hasMany(DetailCommande::class, 'produit_id');
    }
}