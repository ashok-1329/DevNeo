<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPricingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'code_id',
        'item',
        'description',
        'qty',
        'unit',
        'rate',
        'amount',
        'code',
    ];
}