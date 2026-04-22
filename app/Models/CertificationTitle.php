<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationTitle extends Model
{
    protected $fillable = [
        'name',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function certifications()
    {
        return $this->hasMany(UserCertification::class, 'title_id');
    }
}
