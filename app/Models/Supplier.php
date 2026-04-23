<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_category',
        'payment_term',
        'supplier_name',
        'supplier_email',
        'supplier_phone',
        'supplier_address',
        'supplier_abn',
        'supplier_bank_name',
        'supplier_bsb_no',
        'supplier_account_number',
        'supplier_branch',
        'supplier_account_name',
        'supplier_bank_email',
        'payment_terms',
        'supplier_notes',
        'supplier_representative',
        'payment_term_id',
        'payment_term_days',
        'supplier_rank',
        'created_by',
        'updated_by',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getRankLabelAttribute()
    {
        return match ($this->supplier_rank) {
            '1' => 'Do Not Use',
            '2' => 'Use With Caution',
            '3' => 'Satisfactory',
            default => '-',
        };
    }

    public function paymentTerm(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }
}
