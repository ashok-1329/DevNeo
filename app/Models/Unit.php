<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
    ];

    // Optional constants (recommended)
    const TYPE_MATERIAL = 1;
    const TYPE_PLANT = 2;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
}