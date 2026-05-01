<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_code_id',
        'project_name',
        'project_region',
        'project_other_region',
        'project_number',
        'project_description',
        'project_address',
        'project_notes',
        'client_id',
        'client_representative',
        'client_rep_email',
        'superintendent_rep',
        'superintendent_rep_email',
        'client_phone_number',
        'client_address',
        'invoices_sent_to',
        'project_manager',
        'project_engineer',
        'contract_admin',
        'construction_manager',
        'supervisor',
        'contract_notes',
        'contractor_type',
        'superintendent',
        'contract_type',
        'pricing_schedule_file',
        'contract_type_other',
        'contract_number',
        'commencement_date',
        'completion_date',
        'payment_term',
        'payment_term_other',
        'claims_certification_period',
        'claims_certification_period_other',
        'lump_sum',
        'defect_liability_period',
        'schedule_of_rate',
        'contract_value',
        'contract_value_gst',
        'profit_value',
        'provisional_sum_total',
        'provisional_sum_total_gst',
        'assign_profit_margin',
        'assign_profit_margin_value',
        'insurance_percentage',
        'insurance_percentage_value',
        'bank_guarantee_required',
        'practical_completion',
        'custom_practical_completion',
        'practical_completion_amount',
        'final_completion',
        'custom_final_completion',
        'final_completion_amount',
        'cash_retentions_required',
        'cash_practical_completion',
        'custom_cash_practical_completion',
        'cash_practical_completion_amount',
        'cash_final_completion',
        'custom_cash_final_completion',
        'cash_final_completion_amount',
        'is_project_pricing_schedule',
        'is_project_material',
        'is_project_plant',
        'is_project_labourer',
        'status',
        'step',
        'user_id',
    ];

    protected $casts = [
        'commencement_date' => 'date',
        'completion_date'   => 'date',
    ];

    /** Active projects available for selection in payment rules */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /** Payment rules linked to this project */
    public function paymentRules()
    {
        return $this->hasMany(PaymentRule::class, 'project_number', 'project_number');
    }

    public function pricing_schedules()
    {
        return $this->hasMany(ProjectPricingSchedule::class);
    }

    public function assign_codes()
    {
        return $this->hasMany(ProjectAssignCode::class);
    }
}
