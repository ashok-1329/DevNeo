<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Docket;
use App\Models\Supplier;
use App\Models\SubContractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocketController extends Controller
{
    /* ───────────────────────────────────────────── */
    /* VIEW DATA                                     */
    /* ───────────────────────────────────────────── */
    private function viewData(): array
    {
        return [
            'suppliers'      => Supplier::orderBy('supplier_name')->get(),
            'subcontractors' => SubContractor::orderBy('business_name')->get(),
        ];
    }

    /* ───────────────────────────────────────────── */
    /* VALIDATION                                    */
    /* ───────────────────────────────────────────── */
    private function rules(): array
    {
        return [
            'docket_number'  => ['required', 'string', 'max:255'],
            'docket_date'    => ['required', 'date'],
            'supplier'       => ['required', 'integer', 'exists:suppliers,id'],
            'job_code'       => ['required', 'string', 'max:255'],
            'category'       => ['required', 'string', 'max:255'],
            'sub_contractor' => ['nullable', 'integer', 'exists:sub_contractors,id'],
            'submitted_date' => ['required', 'date'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'docket_file'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ];
    }

    private function messages(): array
    {
        return [
            'docket_number.required' => 'Docket number is required.',
            'docket_date.required'   => 'Docket date is required.',
            'supplier.required'      => 'Supplier is required.',
            'job_code.required'      => 'Cost code is required.',
            'category.required'      => 'Category is required.',
            'submitted_date.required' => 'Submitted date is required.',
        ];
    }

    /* ───────────────────────────────────────────── */
    /* INDEX                                         */
    /* ───────────────────────────────────────────── */
    public function index()
    {
        return view('admin.dockets.index');
    }

    /* ───────────────────────────────────────────── */
    /* DATATABLE                                     */
    /* ───────────────────────────────────────────── */
    public function getData()
    {
        $records = Docket::with(['supplierRelation', 'subcontractor'])
            ->orderByDesc('id')
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id,
                'docket_number' => $r->docket_number,
                'supplier'      => $r->supplierRelation?->supplier_name ?? '-',
                'job_code'      => $r->job_code,
                'category'      => $r->category,
                'date'          => $r->docket_date,
                'status'        => $r->status,
            ]);

        return response()->json($records);
    }

    /* ───────────────────────────────────────────── */
    /* CREATE                                        */
    /* ───────────────────────────────────────────── */
    public function create()
    {
        return view('admin.dockets.create', $this->viewData());
    }

    /* ───────────────────────────────────────────── */
    /* STORE                                         */
    /* ───────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $filePath = null;

        if ($request->hasFile('docket_file')) {
            $filePath = $request->file('docket_file')->store('dockets', 'public');
        }

        Docket::create([
            'docket_number'  => $request->docket_number,
            'docket_date'    => $request->docket_date,
            'supplier'       => $request->supplier,
            'job_code'       => $request->job_code,
            'sub_contractor' => $request->sub_contractor,
            'category'       => $request->category,
            'submitted_date' => $request->submitted_date,
            'notes'          => $request->notes,
            'docket_file'    => $filePath,
            'status'         => 1,
            'created_by'     => Auth::id(),
            'updated_by'     => Auth::id(),
        ]);

        return redirect()
            ->route('dockets.index')
            ->with('success', 'Docket created successfully.');
    }

    /* ───────────────────────────────────────────── */
    /* SHOW                                          */
    /* ───────────────────────────────────────────── */
    public function show($id)
    {
        $docket = Docket::with(['supplierRelation', 'subcontractor'])->findOrFail($id);

        return view('admin.dockets.show', compact('docket'));
    }

    /* ───────────────────────────────────────────── */
    /* EDIT                                          */
    /* ───────────────────────────────────────────── */
    public function edit($id)
    {
        $docket = Docket::findOrFail($id);

        return view(
            'admin.dockets.edit',
            array_merge($this->viewData(), compact('docket'))
        );
    }

    /* ───────────────────────────────────────────── */
    /* UPDATE                                        */
    /* ───────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $docket = Docket::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $filePath = $docket->docket_file;

        if ($request->hasFile('docket_file')) {
            $filePath = $request->file('docket_file')->store('dockets', 'public');
        }

        $docket->update([
            'docket_number'  => $request->docket_number,
            'docket_date'    => $request->docket_date,
            'supplier'       => $request->supplier,
            'job_code'       => $request->job_code,
            'sub_contractor' => $request->sub_contractor,
            'category'       => $request->category,
            'submitted_date' => $request->submitted_date,
            'notes'          => $request->notes,
            'docket_file'    => $filePath,
            'updated_by'     => Auth::id(),
        ]);

        return redirect()
            ->route('dockets.index')
            ->with('success', 'Docket updated successfully.');
    }

    /* ───────────────────────────────────────────── */
    /* DELETE                                        */
    /* ───────────────────────────────────────────── */
    public function destroy($id)
    {
        Docket::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
