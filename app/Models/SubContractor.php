<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubContractor extends Model
{
    protected $fillable = [
        'business_name',          // FK → subcontractor_name_lists.id
        'business_name_other',    // free text when "Other" selected
        'rep_name',
        'subcontractor_asset_id',
        'type_of_work',           // FK → subcontractor_type_of_works.id
        'type_of_work_other',     // free text when "Other" selected
        'is_docket',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The selected business name record.
     */
    public function businessName()
    {
        return $this->belongsTo(SubcontractorNameList::class, 'business_name');
    }

    /**
     * The selected type of work record.
     */
    public function workType()
    {
        return $this->belongsTo(SubcontractorTypeOfWork::class, 'type_of_work');
    }

    /**
     * Helper: returns the display label for business name,
     * falling back to the free-text "other" value.
     */
    public function getBusinessNameLabelAttribute(): string
    {
        if ($this->businessName && strtolower($this->businessName->name) === 'other') {
            return $this->business_name_other ?? 'Other';
        }
        return $this->businessName->name ?? '-';
    }

    /**
     * Helper: returns the display label for type of work,
     * falling back to the free-text "other" value.
     */
    public function getWorkTypeLabelAttribute(): string
    {
        if ($this->workType && strtolower($this->workType->name) === 'other') {
            return $this->type_of_work_other ?? 'Other';
        }
        return $this->workType->name ?? '-';
    }
}