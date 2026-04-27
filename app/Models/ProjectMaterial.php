<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterial extends Model
{
    protected $fillable = [
        'project_id',
        'category_id',
        'item',
        'supplier',
        'unit_id',
        'rate',
        'is_docket',
        'add_to_diary',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(DiaryProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierCategory::class);
    }
    
    public function product()
    {
        return $this->belongsTo(DiaryProductCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}