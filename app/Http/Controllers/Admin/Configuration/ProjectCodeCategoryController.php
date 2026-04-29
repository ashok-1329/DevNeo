<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\ProjectCodeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectCodeCategoryController extends Controller
{
    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = ProjectCodeCategory::query();

        $totalRecords    = (clone $query)->count();
        $filteredRecords = $totalRecords;

        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code_name', 'like', "%{$search}%")
                  ->orWhere('assign_margin', 'like', "%{$search}%");
            });
            $filteredRecords = (clone $query)->count();
        }

        $colIndex = $request->input('order.0.column', 0);
        $colDir   = $request->input('order.0.dir', 'asc');
        $colMap   = [0 => 'id', 1 => 'name', 2 => 'code_name', 3 => 'assign_margin', 4 => 'status'];
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
                'DT_RowId'      => 'cc_row_' . $item->id,
                'no'            => $start + $idx + 1,
                'name'          => e($item->name),
                'code_name'     => e($item->code_name),
                'assign_margin' => $item->assign_margin !== null ? $item->assign_margin . '%' : '—',
                'status'        => $badge,
                'action'        => $this->buildActions($item->id),
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
            'code_name'     => 'required|string|max:255',
            'assign_margin' => 'nullable|numeric|min:0|max:100',
            'status'        => 'nullable|in:0,1',
        ], [
            'name.required'      => 'Category Name is required.',
            'code_name.required' => 'Code Name is required.',
            'assign_margin.max'  => 'Assign Margin cannot exceed 100%.',
            'assign_margin.min'  => 'Assign Margin cannot be negative.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $category = ProjectCodeCategory::create([
            'name'          => $request->name,
            'code_name'     => $request->code_name,
            'assign_margin' => $request->assign_margin,
            'status'        => $request->input('status', 1),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Code Category created successfully.',
            'data'    => $category,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $codeCategory)
    {
        $category = ProjectCodeCategory::findOrFail($codeCategory);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'code_name'     => 'required|string|max:255',
            'assign_margin' => 'nullable|numeric|min:0|max:100',
            'status'        => 'nullable|in:0,1',
        ], [
            'name.required'      => 'Category Name is required.',
            'code_name.required' => 'Code Name is required.',
            'assign_margin.max'  => 'Assign Margin cannot exceed 100%.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $category->update([
            'name'          => $request->name,
            'code_name'     => $request->code_name,
            'assign_margin' => $request->assign_margin,
            'status'        => $request->input('status', $category->status),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Code Category updated successfully.',
            'data'    => $category,
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($codeCategory)
    {
        $category = ProjectCodeCategory::findOrFail($codeCategory);
        $category->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Code Category deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.code-categories.update', $id);
        $deleteUrl = route('admin.project.configuration.code-categories.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-code-category"
               data-id="' . $id . '"
               data-url="' . $editUrl . '"
               title="Edit Code Category">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-code-category ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Code Category">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
