<?php

namespace App\Imports;

use App\Models\Supplier;
use App\Models\SupplierCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class SuppliersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row)
    {
        // Map category name to ID
        $category = SupplierCategory::where('name', $row['category'] ?? '')->first();

        return new Supplier([
            'supplier_category'       => $category?->id,
            'supplier_name'           => $row['business_name'] ?? null,
            'supplier_email'          => $row['email'] ?? null,
            'supplier_phone'          => $row['phone'] ?? null,
            'supplier_address'        => $row['address'] ?? null,
            'supplier_abn'            => $row['abn'] ?? null,
            'supplier_bank_name'      => $row['bank_name'] ?? null,
            'supplier_bsb_no'         => $row['bsb_no'] ?? null,
            'supplier_account_number' => $row['account_number'] ?? null,
            'supplier_account_name'   => $row['account_name'] ?? null,
            'supplier_bank_email'     => $row['account_email'] ?? null,
            'payment_terms'           => $row['payment_terms'] ?? null,
            'supplier_notes'          => $row['notes'] ?? null,
            'status'                  => 1,
            'created_by'              => auth()->id(),
        ]);
    }
}
