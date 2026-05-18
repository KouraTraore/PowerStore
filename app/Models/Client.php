<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $timestamps = false;

    protected $fillable = ['nomc', 'prenom', 'tel', 'email', 'adresse'];

    public function scopeSearch(\Illuminate\Database\Eloquent\Builder $query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('prenom', 'like', "%{$search}%")
              ->orWhere('nomc', 'like', "%{$search}%")
              ->orWhere('tel', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
