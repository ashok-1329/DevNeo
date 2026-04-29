<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\PlantCapacity;
use App\Models\PlantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantCapacityController extends Controller
{
    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = PlantCapacity::with('plantType');

        $totalRecords    = (clone $query)->count();
        $filteredRecords = $totalRecords;

        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('plantType', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
            $filteredRecords = (clone $query)->count();
        }

        $colIndex = $request->input('order.0.column', 0);
        $colDir   = $request->input('order.0.dir', 'asc');
        $colMap   = [0 => 'id', 1 => 'name', 3 => 'status'];
        $orderCol = $colMap[$colIndex] ?? 'id';
        $query->orderBy($orderCol, $colDir);

        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $items  = $query->offset($start)->limit($length)->get();

        $rows = $items->map(function ($item, $idx) use ($start) {
            $badge = $item->status
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            return [
                'DT_RowId'   => 'pc_row_' . $item->id,
                'no'         => $start + $idx + 1,
                'name'       => e($item->name),
                'plant_type' => $item->plantType ? e($item->plantType->name) : '—',
                'status'     => $badge,
                'action'     => $this->buildActions($item->id),
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw'),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $rows,
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'plant_type_id' => 'required|exists:plant_types,id',
            'status'        => 'nullable|in:0,1',
        ], [
            'name.required'          => 'Capacity Name is required.',
            'plant_type_id.required' => 'Plant Type is required.',
            'plant_type_id.exists'   => 'Selected Plant Type does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $capacity = PlantCapacity::create([
            'name'          => $request->name,
            'plant_type_id' => $request->plant_type_id,
            'status'        => $request->input('status', 1),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Plant Capacity created successfully.',
            'data'    => $capacity->load('plantType'),
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $plantCapacity)
    {
        $capacity = PlantCapacity::findOrFail($plantCapacity);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'plant_type_id' => 'required|exists:plant_types,id',
            'status'        => 'nullable|in:0,1',
        ], [
            'name.required'          => 'Capacity Name is required.',
            'plant_type_id.required' => 'Plant Type is required.',
            'plant_type_id.exists'   => 'Selected Plant Type does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $capacity->update([
            'name'          => $request->name,
            'plant_type_id' => $request->plant_type_id,
            'status'        => $request->input('status', $capacity->status),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Plant Capacity updated successfully.',
            'data'    => $capacity->load('plantType'),
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($plantCapacity)
    {
        $capacity = PlantCapacity::findOrFail($plantCapacity);
        $capacity->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Plant Capacity deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.plant-capacities.update', $id);
        $deleteUrl = route('admin.project.configuration.plant-capacities.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-plant-capacity"
               data-id="' . $id . '"
               data-url="' . $editUrl . '"
               title="Edit Plant Capacity">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-plant-capacity ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Plant Capacity">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
