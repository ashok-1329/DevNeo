<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryProduct extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'status',
        'created_by',
    ];

    public function category()
    {
        return $this->belongsTo(DiaryProductCategory::class);
    }
}
