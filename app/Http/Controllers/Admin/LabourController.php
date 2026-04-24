<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectLabour;
use App\Models\ProjectRegion;
use App\Models\UserEmploymentType;
use App\Models\LabourPosition;
use App\Models\SupplierCategory;
use App\Models\User;
use Illuminate\Http\Request;

class LabourController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    private function dropdowns(): array
    {
        $regions = ProjectRegion::orderBy('id')->get();

        // Put "Add New" (id == 6) last
        $regions = $regions->sortBy(fn($r) => $r->id == 6 ? PHP_INT_MAX : $r->id)->values();

        return [
            'project_regions'  => $regions,
            'employment_types' => UserEmploymentType::orderBy('name')->get(),
            'labour_positions' => LabourPosition::orderBy('name')->get(),
            'suppliers'        => SupplierCategory::orderBy('name')->get(['id', 'name']),
        ];
    }

    private function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'region'            => 'required',
            'employment_type'   => 'required|exists:user_employment_types,id',
            'employer_position' => 'required|exists:labour_positions,id',
            'employer_supplier' => 'required|exists:supplier_categories,id',
            'employer_rate'     => 'required|numeric|min:0',
        ];
    }

    private function messages(): array
    {
        return [
            'name.required'              => 'Labour name is required.',
            'region.required'            => 'Region is required.',
            'employment_type.required'   => 'Employment type is required.',
            'employment_type.exists'     => 'Selected employment type is invalid.',
            'employer_position.required' => 'Title / position is required.',
            'employer_position.exists'   => 'Selected position is invalid.',
            'employer_supplier.required' => 'Employer is required.',
            'employer_supplier.exists'   => 'Selected employer is invalid.',
            'employer_rate.required'     => 'Rate is required.',
            'employer_rate.numeric'      => 'Rate must be a number.',
            'employer_rate.min'          => 'Rate cannot be negative.',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────

    public function index()
    {
        return view('admin.project.labour.index');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DATA  (DataTables JSON)
    // ──────────────────────────────────────────────────────────────────────────

    public function getData()
    {
        $labours = ProjectLabour::with(['region', 'positionRelation', 'employmentType'])
            ->whereNull('project_id')
            ->orderBy('name')
            ->get()
            ->map(function ($l) {

                // Resolve employer name — no relationship on model yet
                $employerName = '-';
                if ($l->employer_supplier) {
                    $supplier     = SupplierCategory::find($l->employer_supplier);
                    $employerName = $supplier?->name ?? '-';
                }

                return [
                    'id'              => $l->id,
                    'name'            => $l->name,
                    'position'        => $l->positionRelation?->name ?? '-',
                    'employer'        => $employerName,
                    'region'          => $l->region?->name ?? '-',
                    'employment_type' => $l->employmentType?->name ?? '-',
                    'rate'            => number_format((float) $l->rate, 2),
                    'labour_type'     => $l->labour_type == 1 ? 'Internal' : 'External',
                ];
            });

        return response()->json($labours);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // AUTOCOMPLETE
    // ──────────────────────────────────────────────────────────────────────────

    public function autocomplete(Request $request)
    {
        $term  = $request->get('term', '');
        $users = User::where('name', 'like', "%{$term}%")
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users->map(fn($u) => [
            'id'    => $u->id,
            'label' => $u->name,
            'value' => $u->name,
        ]));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // RATE LOOKUP  (placeholder – rate table not built yet)
    // ──────────────────────────────────────────────────────────────────────────

    public function getRate(Request $request)
    {
        // TODO: query rate table once built
        return response()->json(['rate' => null]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.project.labour.create', $this->dropdowns());
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $regionId = $validated['region'];

        if ($request->region == 6 && $request->filled('region_name')) {
            $region   = ProjectRegion::create(['name' => $request->region_name]);
            $regionId = $region->id;
        }

        $user       = User::where('name', $validated['name'])->first();
        $labourType = $user ? 1 : 2;
        $rate       = $user?->contract?->salary_rate ?? $validated['employer_rate'];

        ProjectLabour::create([
            'project_id'        => null,
            'user_id'           => $user?->id,
            'name'              => $validated['name'],
            'employment_type'   => $validated['employment_type'],
            'rate'              => $rate,
            'position'          => $validated['employer_position'],
            'employer_supplier' => $validated['employer_supplier'],
            'labour_type'       => $labourType,
            'region_id'         => $regionId,
        ]);

        return redirect()
            ->route('admin.project.labour.index')
            ->with('success', 'Labour added successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $labour = ProjectLabour::with(['region', 'positionRelation', 'employmentType'])
            ->findOrFail($id);

        $employer = $labour->employer_supplier
            ? SupplierCategory::find($labour->employer_supplier)
            : null;

        return view('admin.project.labour.show', compact('labour', 'employer'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $labour = ProjectLabour::findOrFail($id);

        return view('admin.project.labour.edit', array_merge(
            $this->dropdowns(),
            compact('labour')
        ));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $labour    = ProjectLabour::findOrFail($id);
        $validated = $request->validate($this->rules(), $this->messages());

        $regionId = $validated['region'];

        if ($request->region == 6 && $request->filled('region_name')) {
            $region   = ProjectRegion::create(['name' => $request->region_name]);
            $regionId = $region->id;
        }

        $user = User::where('name', $validated['name'])->first();
        $rate = $user?->contract?->salary_rate ?? $validated['employer_rate'];

        $labour->update([
            'user_id'           => $user?->id,
            'name'              => $validated['name'],
            'employment_type'   => $validated['employment_type'],
            'rate'              => $rate,
            'position'          => $validated['employer_position'],
            'employer_supplier' => $validated['employer_supplier'],
            'labour_type'       => $user ? 1 : 2,
            'region_id'         => $regionId,
        ]);

        return redirect()
            ->route('admin.project.labour.index')
            ->with('success', 'Labour updated successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        ProjectLabour::findOrFail($id)->delete();

        return response()->json(['message' => 'Labour deleted successfully.']);
    }
}