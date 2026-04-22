<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCategory extends Model
{
    protected $fillable = ['name', 'status'];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'supplier_category');
    }
}