<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRule extends Model
{
    protected $fillable = [
        'supplier_name',
        'payment_date',
        'frequency_payment_id',
        'payment_terms',
        'end_date',
        'last_deducted_at',
        'value_inc_gst',
        'project_number',
        'project_code',
        'payment_description',
        'document_path',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'payment_date'     => 'date',
        'end_date'         => 'date',
        'last_deducted_at' => 'date',
    ];

    /** The supplier for this payment rule */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_name', 'id');
    }

    /** The frequency (Weekly / Fortnightly / Monthly …) */
    public function frequencyPayment()
    {
        return $this->belongsTo(FrequencyPayment::class, 'frequency_payment_id');
    }

    /** Resolve back to the project record via project_number */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_number', 'project_number');
    }
}
