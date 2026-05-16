<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;   // ta table n'a pas created_at / updated_at

    protected $fillable = [
        'nomcat',
        'status',
        'image',
        'created_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    // ── Relations ──
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
