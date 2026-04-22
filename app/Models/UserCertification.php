<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id',
        'title_id',
        'custom_title',
        'expiry_date',
        'file',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    // Belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to Certification Title
    public function title()
    {
        return $this->belongsTo(CertificationTitle::class, 'title_id');
    }

    // =========================
    // ACCESSOR (OPTIONAL)
    // =========================

    public function getDisplayTitleAttribute()
    {
        return $this->custom_title ?: ($this->title->name ?? '');
    }
}
