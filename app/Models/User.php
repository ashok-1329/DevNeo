<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'start_date',
        'finish_date',
        'role_id',
        'status',
        'active_status',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /* ── Relationships ── */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function certifications()
    {
        return $this->hasMany(UserCertification::class);
    }

    public function contract()
    {
        return $this->hasOne(UserContract::class);
    }

    public function dockets()
    {
        return $this->hasMany(Docket::class, 'created_by');
    }

    /* ── Accessors for formatted dates ── */

    public function getStartDateDisplayAttribute(): string
    {
        return $this->start_date
            ? \Carbon\Carbon::parse($this->start_date)->format('d/m/Y')
            : '';
    }

    public function getFinishDateDisplayAttribute(): string
    {
        return $this->finish_date
            ? \Carbon\Carbon::parse($this->finish_date)->format('d/m/Y')
            : '';
    }
}


/*
|--------------------------------------------------------------------------
| NOTE: Add formatToDbDate() to your app/helpers.php
|--------------------------------------------------------------------------
|
| If you don't already have this helper, create app/helpers.php and add:
|
|   if (!function_exists('formatToDbDate')) {
|       function formatToDbDate(?string $date): ?string {
|           if (!$date) return null;
|           try {
|               return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
|           } catch (\Throwable) {
|               return $date; // already Y-m-d
|           }
|       }
|   }
|
| Then register it in composer.json autoload.files:
|   "autoload": { "files": ["app/helpers.php"] }
| And run: composer dump-autoload
|
*/