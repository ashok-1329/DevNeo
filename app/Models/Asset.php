<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type',
        'asset_capacity',
        'asset_number',
        'asset_name',
        'asset_code',
        'supplier',
        'registration_number',
        'registration_expiry_date',
        'make_of_asset',
        'model_of_asset',
        'unit',
        'rate',
        'year_of_manufacture',
        'asset_description',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'registration_expiry_date' => 'date',
        'asset_capacity' => 'integer',
        'supplier' => 'integer',
        'unit' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'status' => 'integer',
    ];
}