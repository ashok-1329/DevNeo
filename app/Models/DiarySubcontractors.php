<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiarySubcontractors extends Model
{
    protected $fillable = [
        'subcontractor_id',
        'representative_name',
        'assets_id',
        'work_type',
        'unit',
        'docket',
        'docket_file',
        'submitted_by',
        'diary_date',
        'diary_id',
        'project_id',
        'notes',
        'created_by',
        'updated_by',
        'status',
        'is_publish',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Supplier::class, 'subcontractor_id');
    }

    public function workType()
    {
        return $this->belongsTo(SubcontractorTypeOfWork::class, 'work_type');
    }
}