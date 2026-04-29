<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantCapacity extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_type_id',
        'name',
        'status',
    ];

    protected $casts = [
        'plant_type_id' => 'integer',
        'status' => 'integer',
    ];

    // Relationship
    public function plantType()
    {
        return $this->belongsTo(PlantType::class);
    }
}