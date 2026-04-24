<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLabour extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'employment_type',
        'employer_type',
        'position',
        'rate',
        'employer_supplier',
        'add_to_diary',
        'assign_to_project',
        'labour_type',
        'region_id',
        'labour_agency',
    ];

    protected $casts = [
        'assign_to_project' => 'boolean',
        'add_to_diary' => 'integer',
    ];

    public function employmentType()
    {
        return $this->belongsTo(UserEmploymentType::class, 'employment_type');
    }

    public function positionRelation()
    {
        return $this->belongsTo(LabourPosition::class, 'position');
    }

    public function region()
    {
        return $this->belongsTo(ProjectRegion::class, 'region_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // If you have Project model
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
