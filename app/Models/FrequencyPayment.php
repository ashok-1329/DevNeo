<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequencyPayment extends Model
{
    protected $fillable = ['name', 'status'];

    /** Only active frequency options for dropdowns */
    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('id');
    }
}