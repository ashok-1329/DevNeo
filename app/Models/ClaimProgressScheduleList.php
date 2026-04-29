<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimProgressScheduleList extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'project_id',
        'code_id',
        'qty',
        'claim_date',
    ];

    protected $casts = [
        'claim_id' => 'integer',
        'project_id' => 'integer',
        'code_id' => 'integer',
        'qty' => 'decimal:2',
        'claim_date' => 'date',
    ];
}
