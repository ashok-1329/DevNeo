<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use App\Models\Supplier;
use App\Models\SupplierCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuppliersImport;
use Maatwebsite\Excel\Concerns\ToArray;

class SupplierController extends Controller
{
    private function validationRules(): array
    {
        return [
            'supplier_category'       => 'required|integer|exists:supplier_categories,id',
            'supplier_name'           => 'required|string|max:255',
            'supplier_email'          => 'required|email|max:255',
            'supplier_phone'          => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'supplier_abn'            => ['required', 'string', 'regex:/^\d{11}$/'],
            'supplier_address'        => 'required|string|max:500',
            'supplier_bank_email'     => 'required|email|max:255',
            'supplier_bank_name'      => 'required|string|max:255',
            'supplier_bsb_no'         => ['required', 'string', 'regex:/^\d{3}-?\d{3}$/'],
            'supplier_account_number' => 'required|string|max:50',
            'supplier_account_name'   => 'required|string|max:255',
            'payment_term_id'         => 'required|integer|exists:payment_terms,id',
            'supplier_notes'          => 'nullable|string',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'supplier_category.required'       => 'Please select a category.',
            'supplier_category.exists'         => 'Selected category is invalid.',
            'supplier_name.required'           => 'Business name is required.',
            'supplier_name.max'                => 'Business name must not exceed 255 characters.',
            'supplier_email.required'          => 'Email is required.',
            'supplier_email.email'             => 'Enter a valid email address.',
            'supplier_phone.required'          => 'Phone number is required.',
            'supplier_phone.regex'             => 'Phone number contains invalid characters.',
            'supplier_abn.required'            => 'ABN is required.',
            'supplier_abn.regex'               => 'ABN must be exactly 11 digits.',
            'supplier_address.required'        => 'Address is required.',
            'supplier_address.max'             => 'Address must not exceed 500 characters.',
            'supplier_bank_email.required'     => 'Account email address is required.',
            'supplier_bank_email.email'        => 'Enter a valid account email address.',
            'supplier_bank_name.required'      => 'Bank name is required.',
            'supplier_bsb_no.required'         => 'BSB number is required.',
            'supplier_bsb_no.regex'            => 'BSB must be in format 000-000.',
            'supplier_account_number.required' => 'Account number is required.',
            'supplier_account_name.required'   => 'Account name is required.',
            'payment_term_id.required'         => 'Please select a payment term.',
            'payment_term_id.exists'           => 'Selected payment term is invalid.',
        ];
    }

    public function index()
    {
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = PaymentTerm::active(); // FIX: added ->get()
        return view('admin.suppliers.index', compact('categories', 'paymentTerms'));
    }

    public function getData()
    {
        return response()->json(
            Supplier::with(['category', 'paymentTerm'])->get()
        );
    }

    public function create()
    {
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = PaymentTerm::active(); // FIX: added ->get()
        return view('admin.suppliers.create', compact('categories', 'paymentTerms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $id = (int) $request->payment_term_id;

        $term = PaymentTerm::findOrFail($id);
        
        Supplier::create(array_merge($validated, [
            'payment_term_days' => $term->days,
            'supplier_rank'     => null,
            'status'            => 1,
            'created_by'        => auth()->id(),
        ]));

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $supplier     = Supplier::with(['category', 'paymentTerm'])->findOrFail($id);
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = PaymentTerm::active();
        return view('admin.suppliers.show', compact('supplier', 'categories', 'paymentTerms'));
    }

    public function edit($id)
    {
        $supplier     = Supplier::with(['paymentTerm'])->findOrFail($id); // FIX: eager load paymentTerm for days
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = PaymentTerm::active(); // FIX: added ->get()
        return view('admin.suppliers.edit', compact('supplier', 'categories', 'paymentTerms'));
    }

    public function update(Request $request, $id)
    {
        $rules    = array_merge($this->validationRules(), ['status' => 'required|in:0,1']);
        $messages = array_merge($this->validationMessages(), [
            'status.required' => 'Status is required.',
            'status.in'       => 'Status must be active or inactive.',
        ]);

        $validated = $request->validate($rules, $messages);

        $term = PaymentTerm::findOrFail($validated['payment_term_id']);

        Supplier::findOrFail($id)->update(array_merge($validated, [
            'payment_term_days' => $term->days, // FIX: always sync days on update
            'updated_by'        => auth()->id(),
        ]));

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function updateRank(Request $request, $id)
    {
        $request->validate(['supplier_rank' => 'nullable|in:1,2,3']);

        Supplier::findOrFail($id)->update([
            'supplier_rank' => $request->input('supplier_rank') ?: null,
            'updated_by'    => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Rank updated.']);
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return response()->json(['message' => 'Supplier deleted.']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new SuppliersImport, $request->file('import_file'));
            return redirect()->route('suppliers.index')->with('success', 'Suppliers imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
