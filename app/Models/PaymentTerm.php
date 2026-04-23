<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTerm extends Model
{
    protected $fillable = ['name', 'days', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public static function active()
    {
        return static::where('is_active', true)->orderBy('days')->get();
    }
}