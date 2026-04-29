<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContract extends Model
{
    protected $fillable = [
        'user_id',
        'employment_name',
        'salary_rate',
        'payment_made',
        'timesheet',
        'staff',
        'file_name',
        'file_path',
        'file_extension',
        'notes',
    ];

    protected $casts = [
        'staff' => 'array',
    ];

    // Optional relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
