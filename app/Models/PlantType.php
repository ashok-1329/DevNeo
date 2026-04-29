<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function plantCapacities()
    {
        return $this->hasMany(PlantCapacity::class);
    }
}