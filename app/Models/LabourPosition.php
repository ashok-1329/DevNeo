<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'status',
    ];
}