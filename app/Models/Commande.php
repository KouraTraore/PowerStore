<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
     public $timestamps = false;
    protected $table = 'commandes';

    protected $fillable = ['client_id', 'created_by', 'date_commande', 'statut', 'total_ttc', 'facture_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class, 'facture_id');
    }
}
