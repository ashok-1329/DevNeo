<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectPlant;
use App\Models\Unit;
use App\Models\SupplierCategory;
use App\Models\Project;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    private function viewData(): array
    {
        return [
            'suppliers' => SupplierCategory::orderBy('name')->get(),
            'units'     => Unit::where('type', 2)->orderBy('name')->get(),
            'projects'  => Project::orderBy('project_name')->get(),
        ];
    }

    private function buildRules(): array
    {
        return [
            'plant_type'               => ['required', 'integer'],
            'plant_capacity'           => ['nullable', 'integer'],
            'registration_number'      => ['nullable', 'string', 'max:255'],
            'registration_expiry_date' => ['nullable', 'date'],
            'make_of_asset'            => ['nullable', 'string', 'max:255'],
            'model_of_asset'           => ['nullable', 'string', 'max:255'],
            'plant_name'               => ['required', 'string', 'max:255'],
            'plant_code'               => ['required', 'string', 'max:100'],
            'supplier'                 => ['required', 'integer', 'exists:supplier_categories,id'],
            'unit'                     => ['required', 'string', 'max:100'],
            'rate'                     => ['required', 'numeric', 'min:0'],
            'is_docket'                => ['required', 'in:0,1'],
            'add_to_diary'             => ['required', 'in:0,1'],
        ];
    }

    private function messages(): array
    {
        return [
            'plant_type.required' => 'Plant type is required.',
            'plant_name.required' => 'Plant name is required.',
            'plant_code.required' => 'Plant code is required.',
            'supplier.required'   => 'Supplier is required.',
            'unit.required'       => 'Unit is required.',
            'rate.required'       => 'Rate is required.',
            'is_docket.required'  => 'Select docket option.',
        ];
    }

    public function index()
    {
        return view('admin.plants.index');
    }

    public function getData()
    {
        $records = ProjectPlant::with(['project'])
            ->orderByDesc('id')
            ->get()
            ->map(function ($r) {
                return [
                    'id'              => $r->id,
                    'plant_code'      => $r->plant_code,
                    'plant_type'      => $this->plantTypeLabel($r->plant_type),
                    'plant_capacity'  => $r->plant_capacity,
                    'supplier'        => $r->supplier, // you can join supplier name if needed
                    'plant_name'      => $r->plant_name,
                    'unit'            => $r->unit,
                    'rate'            => number_format($r->rate, 2),
                    'is_docket'       => $r->is_docket ? 'Yes' : 'No',
                ];
            });

        return response()->json($records);
    }

    private function plantTypeLabel($type)
    {
        return match ($type) {
            1 => 'Owned',
            2 => 'Hired',
            default => 'Unknown',
        };
    }

    public function create()
    {
        return view('admin.plants.create', $this->viewData());
    }

    public function store(Request $request)
    {
        $request->validate($this->buildRules(), $this->messages());

        ProjectPlant::create($request->all());

        return redirect()
            ->route('plant.index')
            ->with('success', 'Plant added successfully.');
    }

    public function show($id)
    {
        $plant = ProjectPlant::findOrFail($id);

        return view('admin.plants.show', compact('plant'));
    }

    public function edit($id)
    {
        $plant = ProjectPlant::findOrFail($id);

        return view(
            'admin.plants.edit',
            array_merge($this->viewData(), compact('plant'))
        );
    }

    public function update(Request $request, $id)
    {
        $plant = ProjectPlant::findOrFail($id);

        $request->validate($this->buildRules(), $this->messages());

        $plant->update($request->all());

        return redirect()
            ->route('plants.index')
            ->with('success', 'Plant updated successfully.');
    }

    public function destroy($id)
    {
        ProjectPlant::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
