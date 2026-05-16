<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    public $timestamps = false;
    protected $table = 'factures';

    protected $fillable = ['nomf', 'datef', 'etatf'];

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'facture_id');
    }
}
