<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\PlantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantTypeController extends Controller
{
    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = PlantType::query();

        $totalRecords    = (clone $query)->count();
        $filteredRecords = $totalRecords;

        $search = $request->input('search.value');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
            $filteredRecords = (clone $query)->count();
        }

        $colIndex = $request->input('order.0.column', 0);
        $colDir   = $request->input('order.0.dir', 'asc');
        $colMap   = [0 => 'id', 1 => 'name', 2 => 'status'];
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
                'DT_RowId' => 'plt_row_' . $item->id,
                'no'       => $start + $idx + 1,
                'name'     => e($item->name),
                'status'   => $badge,
                'action'   => $this->buildActions($item->id),
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
            'name'   => 'required|string|max:255|unique:plant_types,name',
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Plant Type Name is required.',
            'name.unique'   => 'This Plant Type already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $plantType = PlantType::create([
            'name'   => $request->name,
            'status' => $request->input('status', 1),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Plant Type created successfully.',
            'data'    => $plantType,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $plantType)
    {
        $type = PlantType::findOrFail($plantType);

        $validator = Validator::make($request->all(), [
            'name'   => "required|string|max:255|unique:plant_types,name,{$type->id}",
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Plant Type Name is required.',
            'name.unique'   => 'This Plant Type already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $type->update([
            'name'   => $request->name,
            'status' => $request->input('status', $type->status),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Plant Type updated successfully.',
            'data'    => $type,
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($plantType)
    {
        $type = PlantType::findOrFail($plantType);

        // Prevent deletion if plant capacities exist
        if ($type->plantCapacities()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cannot delete: Plant Type has associated Plant Capacities.',
            ], 422);
        }

        $type->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Plant Type deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.plant-types.update', $id);
        $deleteUrl = route('admin.project.configuration.plant-types.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-plant-type"
               data-id="' . $id . '"
               data-url="' . $editUrl . '"
               title="Edit Plant Type">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-plant-type ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Plant Type">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
