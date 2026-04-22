<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuppliersImport;

class SupplierController extends Controller
{
    private const PAYMENT_TERMS = [
        '8 EOM',
        '14 Days',
        '28 Days',
        '30 Days',
        '7 Days',
        '30 Days EOM',
        '10 Days',
        'Other',
    ];

    public function index()
    {
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = self::PAYMENT_TERMS;
        return view('admin.suppliers.index', compact('categories', 'paymentTerms'));
    }

    public function getData()
    {
        $suppliers = Supplier::with('category')->get();
        return response()->json($suppliers);
    }

    public function create()
    {
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = self::PAYMENT_TERMS;
        return view('admin.suppliers.create', compact('categories', 'paymentTerms'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'supplier_name'           => 'required|string|max:255',
            'supplier_email'          => 'required|email|max:255',
            'supplier_phone'          => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\s\.]+$/'],
            'supplier_address'        => 'required|string|max:500',
            'supplier_category'       => 'required|integer|exists:supplier_categories,id',
            'supplier_abn'            => ['required', 'string', 'max:20', 'regex:/^\d{11}$/'],
            'supplier_bank_name'      => 'required|string|max:255',
            'supplier_bsb_no'         => ['required', 'string', 'max:10', 'regex:/^\d{3}-?\d{3}$/'],
            'supplier_account_number' => 'required|string|max:50',
            'supplier_account_name'   => 'required|string|max:255',
            'supplier_bank_email'     => 'required|email|max:255',
            'payment_terms'           => 'required|string|in:' . implode(',', self::PAYMENT_TERMS),
            'supplier_notes'          => 'nullable|string',
        ]);

        Supplier::create([
            'supplier_category'       => $validated['supplier_category'],
            'supplier_name'           => $validated['supplier_name'],
            'supplier_email'          => $validated['supplier_email'],
            'supplier_phone'          => $validated['supplier_phone'],
            'supplier_address'        => $validated['supplier_address'],
            'supplier_abn'            => $validated['supplier_abn'],
            'supplier_bank_name'      => $validated['supplier_bank_name'],
            'supplier_bsb_no'         => $validated['supplier_bsb_no'],
            'supplier_account_number' => $validated['supplier_account_number'],
            'supplier_account_name'   => $validated['supplier_account_name'],
            'supplier_bank_email'     => $validated['supplier_bank_email'],
            'payment_terms'           => $validated['payment_terms'],
            'supplier_notes'          => $validated['supplier_notes'] ?? null,
            'supplier_rank'           => null,
            'status'                  => 1,
            'created_by'              => auth()->id(),
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $supplier     = Supplier::with('category')->findOrFail($id);
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = self::PAYMENT_TERMS;
        return view('admin.suppliers.show', compact('supplier', 'categories', 'paymentTerms'));
    }

    public function edit($id)
    {
        $supplier     = Supplier::findOrFail($id);
        $categories   = SupplierCategory::where('status', 1)->get();
        $paymentTerms = self::PAYMENT_TERMS;
        return view('admin.suppliers.edit', compact('supplier', 'categories', 'paymentTerms'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_name'           => 'required|string|max:255',
            'supplier_email'          => 'required|email|max:255',
            'supplier_phone'          => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\s\.]+$/'],
            'supplier_address'        => 'required|string|max:500',
            'supplier_category'       => 'required|integer|exists:supplier_categories,id',
            'supplier_abn'            => ['required', 'string', 'max:20', 'regex:/^\d{11}$/'],
            'supplier_bank_name'      => 'required|string|max:255',
            'supplier_bsb_no'         => ['required', 'string', 'max:10', 'regex:/^\d{3}-?\d{3}$/'],
            'supplier_account_number' => 'required|string|max:50',
            'supplier_account_name'   => 'required|string|max:255',
            'supplier_bank_email'     => 'required|email|max:255',
            'payment_terms'           => 'required|string|in:' . implode(',', self::PAYMENT_TERMS),
            'supplier_notes'          => 'nullable|string',
            'supplier_representative' => 'nullable|string|max:255',
            'supplier_branch'         => 'nullable|string|max:255',
            'payment_term'            => 'nullable|integer|min:0',
            'status'                  => 'required|in:0,1',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update(array_merge($validated, ['updated_by' => auth()->id()]));

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Inline rank update from listing (AJAX).
     */
    public function updateRank(Request $request, $id)
    {
        $request->validate([
            'supplier_rank' => 'nullable|in:1,2,3',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'supplier_rank' => $request->input('supplier_rank') ?: null,
            'updated_by'    => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Rank updated.']);
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Supplier deleted.'
        ]);
    }

    /**
     * Import suppliers from Excel/CSV.
     * Requires: composer require maatwebsite/excel
     */
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
