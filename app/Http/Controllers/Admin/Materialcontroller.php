<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectMaterial;
use App\Models\Unit;
use App\Models\SupplierCategory;
use App\Models\DiaryProductCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /* ───────────────────────────────────────────── */
    /* VIEW DATA                                     */
    /* ───────────────────────────────────────────── */
    private function viewData(): array
    {
        return [
            'productCategories' => DiaryProductCategory::orderBy('name')->get(),
            'suppliers'         => SupplierCategory::orderBy('name')->get(),
            'materialUnits'     => Unit::where('type', 1)->orderBy('name')->get(),
            'project'           => Project::orderBy('project_name')->get(),
        ];
    }

    /* ───────────────────────────────────────────── */
    /* VALIDATION                                    */
    /* ───────────────────────────────────────────── */
    private function buildRules(): array
    {
        return [
            'project_id'   => ['required', 'integer', 'exists:projects,id'],
            'category_id'  => ['required', 'integer', 'exists:diary_product_categories,id'],
            'item'         => ['required', 'string', 'max:255'],
            'supplier'     => ['required', 'integer', 'exists:supplier_categories,id'],
            'unit_id'      => ['required', 'integer', 'exists:units,id'],
            'rate'         => ['required', 'numeric', 'min:0'],
            'is_docket'    => ['required', 'in:0,1'],
            'add_to_diary' => ['required', 'in:0,1'],
        ];
    }

    private function messages(): array
    {
        return [
            'project_id.required'   => 'Project is required.',
            'category_id.required'  => 'Category is required.',
            'item.required'         => 'Item is required.',
            'supplier.required'     => 'Supplier is required.',
            'unit_id.required'      => 'Unit is required.',
            'rate.required'         => 'Rate is required.',
            'is_docket.required'    => 'Select docket option.',
            'add_to_diary.required' => 'Select diary option.',
        ];
    }

    /* ───────────────────────────────────────────── */
    /* INDEX                                         */
    /* ───────────────────────────────────────────── */
    public function index()
    {
        return view('admin.materials.index');
    }

    /* ───────────────────────────────────────────── */
    /* DATATABLE                                     */
    /* ───────────────────────────────────────────── */
    public function getData()
    {
        $records = ProjectMaterial::with(['category', 'supplier', 'unit'])
            ->orderByDesc('id')
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id,
                'category_name' => $r->category?->name ?? '-',
                'item'          => $r->item,
                'supplier_name' => $r->supplier?->supplier_name ?? '-',
                'unit_name'     => $r->unit?->name ?? '-',
                'rate'          => $r->rate,
                'is_docket'     => $r->is_docket ? 'Yes' : 'No',
                'add_to_diary'  => $r->add_to_diary ? 'Yes' : 'No',
            ]);

        return response()->json($records);
    }

    /* ───────────────────────────────────────────── */
    /* CREATE                                        */
    /* ───────────────────────────────────────────── */
    public function create()
    {
        return view('admin.materials.create', $this->viewData());
    }

    /* ───────────────────────────────────────────── */
    /* STORE                                         */
    /* ───────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate($this->buildRules(), $this->messages());

        ProjectMaterial::create([
            'project_id'   => $request->project_id,
            'category_id'  => $request->category_id,
            'item'         => $request->item,
            'supplier'     => $request->supplier,
            'unit_id'      => $request->unit_id,
            'rate'         => $request->rate,
            'is_docket'    => $request->is_docket,
            'add_to_diary' => $request->add_to_diary,
        ]);

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material added successfully.');
    }

    /* ───────────────────────────────────────────── */
    /* SHOW                                          */
    /* ───────────────────────────────────────────── */
    public function show($id)
    {
        $material = ProjectMaterial::with(['category', 'supplier', 'unit'])
            ->findOrFail($id);

        return view('admin.materials.show', compact('material'));
    }

    /* ───────────────────────────────────────────── */
    /* EDIT                                          */
    /* ───────────────────────────────────────────── */
    public function edit($id)
    {
        $material = ProjectMaterial::findOrFail($id);

        return view(
            'admin.materials.edit',
            array_merge($this->viewData(), compact('material'))
        );
    }

    /* ───────────────────────────────────────────── */
    /* UPDATE                                        */
    /* ───────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $material = ProjectMaterial::findOrFail($id);

        $request->validate($this->buildRules(), $this->messages());

        $material->update([
            'project_id'   => $request->project_id,
            'category_id'  => $request->category_id,
            'item'         => $request->item,
            'supplier'     => $request->supplier,
            'unit_id'      => $request->unit_id,
            'rate'         => $request->rate,
            'is_docket'    => $request->is_docket,
            'add_to_diary' => $request->add_to_diary,
        ]);

        return redirect()
            ->route('materials.index')
            ->with('success', 'Material updated successfully.');
    }

    /* ───────────────────────────────────────────── */
    /* DELETE                                        */
    /* ───────────────────────────────────────────── */
    public function destroy($id)
    {
        ProjectMaterial::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
