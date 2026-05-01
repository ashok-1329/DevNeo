<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectManagePlant extends Model
{
    protected $table = 'project_manage_plants';

    protected $fillable = [
        'record_id',
        'project_id',
        'plant_id',
        'user_id',
        'periods',
    ];

    protected $casts = [
        'periods' => 'array',
    ];

    // Relationships (optional but recommended)

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function plant()
    {
        return $this->belongsTo(ProjectPlant::class, 'plant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
