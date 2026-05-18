<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    public $timestamps = false; // si votre table n'a pas created_at/updated_at

    protected $fillable = [
        'username', 'email', 'password', 'role', 'nom', 'prenom', 'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    // Mutator pour hasher automatiquement le mot de passe
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
// Relation : les produits créés par cet utilisateur
public function produits()
{
    return $this->hasMany(Product::class, 'created_by');
}

// Relation : les commandes créées par cet utilisateur
public function commandes()
{
    return $this->hasMany(Commande::class, 'created_by');
}
    // Vérifie si l'utilisateur est super admin
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';

        }
}
