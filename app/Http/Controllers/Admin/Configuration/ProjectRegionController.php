<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\ProjectRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectRegionController extends Controller
{
    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = ProjectRegion::query();

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
                'DT_RowId' => 'pr_row_' . $item->id,
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
            'name'   => 'required|string|max:255|unique:project_regions,name',
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Project Region Name is required.',
            'name.unique'   => 'This Project Region already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $region = ProjectRegion::create([
            'name'   => $request->name,
            'status' => $request->input('status', 1),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Project Region created successfully.',
            'data'    => $region,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $projectRegion)
    {
        $region = ProjectRegion::findOrFail($projectRegion);

        $validator = Validator::make($request->all(), [
            'name'   => "required|string|max:255|unique:project_regions,name,{$region->id}",
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Project Region Name is required.',
            'name.unique'   => 'This Project Region already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $region->update([
            'name'   => $request->name,
            'status' => $request->input('status', $region->status),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Project Region updated successfully.',
            'data'    => $region,
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($projectRegion)
    {
        $region = ProjectRegion::findOrFail($projectRegion);
        $region->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Project Region deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.project-regions.update', $id);
        $deleteUrl = route('admin.project.configuration.project-regions.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-project-region"
               data-id="' . $id . '"
               data-url="' . $editUrl . '"
               title="Edit Project Region">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-project-region ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Project Region">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
