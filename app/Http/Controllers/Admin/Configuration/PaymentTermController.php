<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTermController extends Controller
{
    // ── DataTable (AJAX) ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $query = PaymentTerm::query();

        $totalRecords    = (clone $query)->count();
        $filteredRecords = $totalRecords;

        // Global search
        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('days', 'like', "%{$search}%");
            });
            $filteredRecords = (clone $query)->count();
        }

        // Ordering
        $colIndex = $request->input('order.0.column', 0);
        $colDir   = $request->input('order.0.dir', 'asc');
        $colMap   = [0 => 'id', 1 => 'name', 2 => 'days', 3 => 'is_active'];
        $orderCol = $colMap[$colIndex] ?? 'id';
        $query->orderBy($orderCol, $colDir);

        // Pagination
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $items  = $query->offset($start)->limit($length)->get();

        $rows = $items->map(function ($item, $idx) use ($start) {
            $badge = $item->is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            return [
                'DT_RowId'  => 'pt_row_' . $item->id,
                'no'        => $start + $idx + 1,
                'name'      => e($item->name),
                'days'      => $item->days,
                'is_active' => $badge,
                'action'    => $this->buildActions($item->id),
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
            'name'      => 'required|string|max:255',
            'days'      => 'required|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Payment Term Name is required.',
            'days.required' => 'Days field is required.',
            'days.integer'  => 'Days must be a number.',
            'days.min'      => 'Days must be at least 0.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $term = PaymentTerm::create([
            'name'      => $request->name,
            'days'      => $request->days,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Payment Term created successfully.',
            'data'    => $term,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, $paymentTerm)
    {
        $term = PaymentTerm::findOrFail($paymentTerm);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'days'      => 'required|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Payment Term Name is required.',
            'days.required' => 'Days field is required.',
            'days.integer'  => 'Days must be a number.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $term->update([
            'name'      => $request->name,
            'days'      => $request->days,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Payment Term updated successfully.',
            'data'    => $term,
        ]);
    }

    // ── Destroy ───────────────────────────────────────────────────────────────
    public function destroy($paymentTerm)
    {
        $term = PaymentTerm::findOrFail($paymentTerm);
        $term->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Payment Term deleted successfully.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function buildActions(int $id): string
    {
        $editUrl   = route('admin.project.configuration.payment-terms.update', $id);
        $deleteUrl = route('admin.project.configuration.payment-terms.destroy', $id);

        return '
            <a href="javascript:void(0)"
               class="action-tooltip btn-edit-payment-term"
               data-id="' . $id . '"
               data-url="' . $editUrl . '"
               title="Edit Payment Term">
               <i class="fa fa-pencil"></i>
            </a>
            <a href="javascript:void(0)"
               class="action-tooltip btn-delete-payment-term ms-2"
               data-id="' . $id . '"
               data-url="' . $deleteUrl . '"
               title="Delete Payment Term">
               <i class="fa fa-trash text-danger"></i>
            </a>';
    }
}
