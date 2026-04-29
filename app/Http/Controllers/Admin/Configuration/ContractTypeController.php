<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractTypeController extends Controller
{
    /** Setting.type value that identifies contract-type records */
    const SETTING_TYPE = 1;

    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = Setting::where('type', self::SETTING_TYPE);

        $totalRecords    = (clone $query)->count();
        $filteredRecords = $totalRecords;

        // Global search
        $search = $request->input('search.value');
        if ($search) {
            $query->where('value', 'like', "%{$search}%");
            $filteredRecords = (clone $query)->count();
        }

        // Ordering
        $colIndex  = $request->input('order.0.column', 0);
        $colDir    = $request->input('order.0.dir', 'asc');
        $colMap    = [0 => 'id', 1 => 'value'];
        $orderCol  = $colMap[$colIndex] ?? 'id';
        $query->orderBy($orderCol, $colDir);

        // Pagination
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $items  = $query->offset($start)->limit($length)->get();

        $rows = $items->map(function ($item, $idx) use ($start) {
            return [
                'DT_RowId' => 'contract_row_' . $item->id,
                'no'       => $start + $idx + 1,
                'value'    => e($item->value),
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
            'value' => 'required|string|max:255|unique:settings,value,NULL,id,type,' . self::SETTING_TYPE,
        ], [
            'value.required' => 'Contract Type Name is required.',
            'value.unique'   => 'This Contract Type already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $setting = Setting::create([
            'name'  => 'Contract Type',
            'type'  => self::SETTING_TYPE,
            'key'   => 'contract_type',
            'value' => $request->value,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Contract Type created successfully.',
            'data'    => $setting,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $contractType)
    {
        $setting = Setting::where('type', self::SETTING_TYPE)->findOrFail($contractType);

        $validator = Validator::make($request->all(), [
            'value' => "required|string|max:255|unique:settings,value,{$setting->id},id,type," . self::SETTING_TYPE,
        ], [
            'value.required' => 'Contract Type Name is required.',
            'value.unique'   => 'This Contract Type already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $setting->update(['value' => $request->value]);

        return response()->json([
            'status'  => true,
            'message' => 'Contract Type updated successfully.',
            'data'    => $setting,
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($contractType)
    {
        $setting = Setting::where('type', self::SETTING_TYPE)->findOrFail($contractType);
        $setting->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Contract Type deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.contract-types.update', $id);
        $deleteUrl = route('admin.project.configuration.contract-types.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-contract"
               data-id="' . $id . '"
               data-fetch-url="' . $editUrl . '"
               title="Edit Contract Type">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-contract ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Contract Type">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
