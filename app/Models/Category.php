<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


    public $timestamps = false;

    protected $fillable = [
        'nomcat',
        'status',
        'image',
        'created_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'description',
    ];

    protected $appends = ['description'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder.png');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getNameAttribute()
    {
        return $this->nomcat;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['rejection_reason'] = $value;
    }

    public function getDescriptionAttribute()
    {
        return $this->rejection_reason;
    }

    /**
     * Get status badge text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'approved' => 'Approuvée',
            'pending' => 'En attente',
            'rejected' => 'Rejetée',
            default => 'Inconnue',
        };
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope: Search by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nomcat', 'like', '%' . $search . '%')
                        ->orWhere('rejection_reason', 'like', '%' . $search . '%');
        }
        return $query;
    }

    //

}
