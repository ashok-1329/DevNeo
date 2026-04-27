<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docket extends Model
{
    protected $fillable = [
        'invoice_id',
        'project_number',
        'docket_number',
        'docket_date',
        'supplier',
        'job_code',
        'sub_contractor',
        'category',
        'notes',
        'is_invoice',
        'docket_file',
        'manager_id',
        'e_signature_file',
        'approval_e_signature_file',
        'submitted_date',
        'status',
        'created_by',
        'updated_by',
    ];

    public function supplierRelation()
    {
        return $this->belongsTo(SubContractor::class, 'supplier');
    }

    public function subcontractor()
    {
        return $this->belongsTo(SubContractor::class, 'sub_contractor');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
