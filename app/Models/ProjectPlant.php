<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPlant extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'plant_id',
        'plant_type',
        'plant_capacity',
        'registration_number',
        'registration_expiry_date',
        'make_of_asset',
        'model_of_asset',
        'plant_name',
        'plant_code',
        'supplier',
        'unit',
        'rate',
        'is_docket',
        'add_to_diary',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'plant_type' => 'integer',
        'plant_capacity' => 'integer',
        'registration_expiry_date' => 'date',
        'is_docket' => 'integer',
        'add_to_diary' => 'integer',
    ];

    // Relationship
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}