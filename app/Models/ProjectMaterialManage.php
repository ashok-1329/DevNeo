<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterialManage extends Model
{
    protected $table = 'project_material_manages';

    protected $fillable = [
        'record_id',
        'project_id',
        'user_id',
        'periods',
    ];

    protected $casts = [
        'periods' => 'array',
    ];

    // Relationships (optional)

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}