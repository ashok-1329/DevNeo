<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCodeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_name',
        'assign_margin',
        'status',
    ];
}