<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManageLabour extends Model
{
    use HasFactory;

    protected $fillable = [
        'labour_id',
        'project_id',
        'user_id',
        'days_in_month',
        'total_days',
        'present_days',
        'periods',
    ];

    protected $casts = [
        'labour_id' => 'integer',
        'project_id' => 'integer',
        'user_id' => 'integer',
        'days_in_month' => 'integer',
        'total_days' => 'integer',
        'present_days' => 'integer',
        'periods' => 'array',
    ];

    // Relationships
    public function labour()
    {
        return $this->belongsTo(LabourPosition::class, 'labour_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
