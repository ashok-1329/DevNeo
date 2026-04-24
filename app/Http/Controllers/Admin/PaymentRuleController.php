<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrequencyPayment;
use App\Models\PaymentRule;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentRuleController extends Controller
{
    // ── Validation ─────────────────────────────────────────────────────────────

    private function validationRules(bool $isUpdate = false): array
    {
        return [
            'supplier_name'        => 'required|integer|exists:suppliers,id',
            'payment_date'         => 'required|date|after_or_equal:today',
            'frequency_payment_id' => 'required|integer|exists:frequency_payments,id',
            'end_date'             => 'required|date|after_or_equal:today',
            'value_inc_gst'        => 'required|string|max:255',
            'project_number'       => 'required|string|max:255',
            'project_code'         => 'nullable|string|max:255',
            'payment_description'  => 'required|string',
            'document'             => $isUpdate
                ? 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
                : 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'supplier_name.required'        => 'Please select a supplier.',
            'supplier_name.exists'          => 'Selected supplier is invalid.',
            'payment_date.required'         => 'Payment date is required.',
            'payment_date.after_or_equal'   => 'Payment date cannot be in the past.',
            'frequency_payment_id.required' => 'Please select a frequency of payment.',
            'frequency_payment_id.exists'   => 'Selected frequency is invalid.',
            'end_date.required'             => 'End date is required.',
            'end_date.after_or_equal'       => 'End date cannot be in the past.',
            'value_inc_gst.required'        => 'Value (inc. GST) is required.',
            'project_number.required'       => 'Please select a project number.',
            'payment_description.required'  => 'Payment description is required.',
            'document.required'             => 'Please upload a supporting document.',
            'document.mimes'                => 'Document must be a PDF, Word, or image file.',
            'document.max'                  => 'Document must not exceed 10 MB.',
        ];
    }

    // ── Index ──────────────────────────────────────────────────────────────────

    public function index()
    {
        $suppliers         = Supplier::orderBy('supplier_name')->get();
        $frequencyPayments = FrequencyPayment::active()->get();
        return view('admin.payment_rules.index', compact('suppliers', 'frequencyPayments'));
    }

    // ── DataTable AJAX ─────────────────────────────────────────────────────────

    public function getData()
    {
        $rows = PaymentRule::with(['supplier', 'frequencyPayment'])->get();
        return response()->json($rows);
    }

    // ── Create ─────────────────────────────────────────────────────────────────

    public function create()
    {
        $suppliers         = Supplier::orderBy('supplier_name')->get();
        $frequencyPayments = FrequencyPayment::active()->get();
        $projects          = Project::active()
            ->select('id', 'project_number', 'project_code_id')
            ->orderBy('project_number')
            ->get();
        $today             = now()->format('Y-m-d');
        return view(
            'admin.payment_rules.create',
            compact('suppliers', 'frequencyPayments', 'projects', 'today')
        );
    }

    // ── Store ──────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->merge([
            'payment_date' => formatToDbDate($request->payment_date),
            'end_date'     => formatToDbDate($request->end_date),
        ]);


        $validated = $request->validate(
            $this->validationRules(false),
            $this->validationMessages()
        );

        $docPath = null;
        if ($request->hasFile('document')) {
            $docPath = $request->file('document')
                ->store('payment-rules/documents', 'public');
        }

        PaymentRule::create(array_merge($validated, [
            'document_path' => $docPath,
            'status'        => 1,
            'created_by'    => auth()->id(),
        ]));

        return redirect()->route('payment-rules.index')
            ->with('success', 'Payment rule created successfully.');
    }

    // ── Show ───────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $paymentRule = PaymentRule::with(['supplier', 'frequencyPayment'])->findOrFail($id);
        return view('admin.payment_rules.show', compact('paymentRule'));
    }

    // ── Edit ───────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $paymentRule       = PaymentRule::findOrFail($id);
        $suppliers         = Supplier::orderBy('supplier_name')->get();
        $frequencyPayments = FrequencyPayment::active()->get();
        $projects          = Project::active()
            ->select('id', 'project_number', 'project_code_id')
            ->orderBy('project_number')
            ->get();
        $today             = now()->format('Y-m-d');
        return view(
            'admin.payment_rules.edit',
            compact('paymentRule', 'suppliers', 'frequencyPayments', 'projects', 'today')
        );
    }

    // ── Update ─────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $paymentRule = PaymentRule::findOrFail($id);

        $request->merge([
            'payment_date' => formatToDbDate($request->payment_date),
            'end_date'     => formatToDbDate($request->end_date),
        ]);


        $validated = $request->validate(
            $this->validationRules(true),
            $this->validationMessages()
        );

        $docPath = $paymentRule->document_path;
        if ($request->hasFile('document')) {
            // Remove old file
            if ($docPath) {
                Storage::disk('public')->delete($docPath);
            }
            $docPath = $request->file('document')
                ->store('payment-rules/documents', 'public');
        }

        $paymentRule->update(array_merge($validated, [
            'document_path' => $docPath,
            'updated_by'    => auth()->id(),
        ]));

        return redirect()->route('payment-rules.index')
            ->with('success', 'Payment rule updated successfully.');
    }

    // ── Destroy ────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $paymentRule = PaymentRule::findOrFail($id);

        if ($paymentRule->document_path) {
            Storage::disk('public')->delete($paymentRule->document_path);
        }

        $paymentRule->delete();

        return response()->json(['message' => 'Payment rule deleted.']);
    }
}
