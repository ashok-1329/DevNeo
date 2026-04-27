<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubContractor;
use App\Models\SubcontractorNameList;
use App\Models\SubcontractorTypeOfWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcontractorController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // Shared view data
    // ──────────────────────────────────────────────────────────────────────────
    private function viewData(): array
    {
        return [
            'businessNames' => SubcontractorNameList::orderBy('name')->get(),
            'workTypes'     => SubcontractorTypeOfWork::orderBy('name')->get(),
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Dynamic validation — adds "required" to *_other when "Other" is selected
    // ──────────────────────────────────────────────────────────────────────────
    private function buildRules(Request $request): array
    {
        $businessNameOtherRule = ['nullable', 'string', 'max:255'];

        if (!empty($request->business_name)) {
            $bn = SubcontractorNameList::find($request->business_name);
            if ($bn && strtolower($bn->name) === 'other') {
                $businessNameOtherRule = ['required', 'string', 'max:255'];
            }
        }

        $typeOfWorkOtherRule = ['nullable', 'string', 'max:255'];

        if (!empty($request->type_of_work)) {
            $tw = SubcontractorTypeOfWork::find($request->type_of_work);
            if ($tw && strtolower($tw->name) === 'other') {
                $typeOfWorkOtherRule = ['required', 'string', 'max:255'];
            }
        }

        return [
            'business_name'          => ['required', 'integer', 'exists:subcontractor_name_lists,id'],
            'business_name_other'    => $businessNameOtherRule,
            'rep_name'               => ['required', 'string', 'max:255'],
            'subcontractor_asset_id' => ['nullable', 'string', 'max:255'],
            'type_of_work'           => ['required', 'integer', 'exists:subcontractor_type_of_works,id'],
            'type_of_work_other'     => $typeOfWorkOtherRule,
            'is_docket'              => ['required', 'in:1,0'],
        ];
    }

    private function messages(): array
    {
        return [
            'business_name.required'       => 'Business Name is required.',
            'business_name.exists'         => 'Selected business name is invalid.',
            'business_name_other.required' => 'Please specify the other business name.',
            'rep_name.required'            => 'Representative Name is required.',
            'type_of_work.required'        => 'Type of Work is required.',
            'type_of_work.exists'          => 'Selected work type is invalid.',
            'type_of_work_other.required'  => 'Please specify the other work type.',
            'is_docket.required'           => 'Please select Yes or No for Dockets Required.',
            'is_docket.in'                 => 'Dockets Required must be Yes or No.',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────
    public function index()
    {
        return view('admin.subcontractors.index');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DATATABLE JSON
    // ──────────────────────────────────────────────────────────────────────────
    public function getData()
    {
        $records = SubContractor::with(['businessName', 'workType'])
            ->orderByDesc('id')
            ->get()
            ->map(fn($r) => [
                'id'                     => $r->id,
                // If "Other" was chosen, append the free-text value
                'business_name'          => $r->businessName
                    ? (strtolower($r->businessName->name) === 'other' && $r->business_name_other
                        ? $r->business_name_other
                        : $r->businessName->name)
                    : '-',
                'rep_name'               => $r->rep_name ?? '-',
                'subcontractor_asset_id' => $r->subcontractor_asset_id ?? '-',
                'work_type_name'         => $r->workType
                    ? (strtolower($r->workType->name) === 'other' && $r->type_of_work_other
                        ? $r->type_of_work_other
                        : $r->workType->name)
                    : '-',
                'is_docket'              => $r->is_docket ? 'Yes' : 'No',
                'status'                 => $r->status,
            ]);

        return response()->json($records);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.subcontractors.create', $this->viewData());
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate($this->buildRules($request), $this->messages());

        SubContractor::create([
            'business_name'          => $request->business_name,
            'business_name_other'    => $request->business_name_other,
            'rep_name'               => $request->rep_name,
            'subcontractor_asset_id' => $request->subcontractor_asset_id,
            'type_of_work'           => $request->type_of_work,
            'type_of_work_other'     => $request->type_of_work_other,
            'is_docket'              => $request->is_docket,
            'status'                 => 1,
            'created_by'             => Auth::id(),
            'updated_by'             => Auth::id(),
        ]);

        return redirect()
            ->route('subcontractors.index')
            ->with('success', 'Subcontractor added successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────────────────
    public function show($id)
    {
        $diarySubcontractor = SubContractor::with(['businessName', 'workType'])
            ->findOrFail($id);

        return view('admin.subcontractors.show', compact('diarySubcontractor'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $diarySubcontractor = SubContractor::findOrFail($id);

        return view(
            'admin.subcontractors.edit',
            array_merge($this->viewData(), compact('diarySubcontractor'))
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $diarySubcontractor = SubContractor::findOrFail($id);

        $request->validate($this->buildRules($request), $this->messages());

        $diarySubcontractor->update([
            'business_name'          => $request->business_name,
            'business_name_other'    => $request->business_name_other,
            'rep_name'               => $request->rep_name,
            'subcontractor_asset_id' => $request->subcontractor_asset_id,
            'type_of_work'           => $request->type_of_work,
            'type_of_work_other'     => $request->type_of_work_other,
            'is_docket'              => $request->is_docket,
            'updated_by'             => Auth::id(),
        ]);

        return redirect()
            ->route('subcontractors.index')
            ->with('success', 'Subcontractor updated successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        SubContractor::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STATUS TOGGLE (AJAX)
    // ──────────────────────────────────────────────────────────────────────────
    public function statusUpdate(Request $request, $id)
    {
        $record = SubContractor::find($id);

        if ($record) {
            $record->update(['status' => $request->status]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
