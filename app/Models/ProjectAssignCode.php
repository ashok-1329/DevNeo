<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'code_id',
        'assign_margin',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'code_id' => 'integer',
    ];

    // Relationships (recommended)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function code()
    {
        return $this->belongsTo(ProjectCodeCategory::class, 'code_id');
    }
}