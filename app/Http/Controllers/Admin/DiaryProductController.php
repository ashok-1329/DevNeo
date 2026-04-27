<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaryProduct;
use App\Models\DiaryProductCategory;
use Illuminate\Http\Request;

class DiaryProductController extends Controller
{
    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:diary_product_categories,id',
        ];
    }

    private function messages(): array
    {
        return [
            'name.required'        => 'Product name is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
        ];
    }

    public function index()
    {
        return view('admin.diary-products.index');
    }

    public function getData()
    {
        $products = DiaryProduct::with('category')
            ->select('id', 'name', 'category_id', 'status', 'created_at')
            ->orderBy('name')
            ->get()
            ->map(function ($p) {
                return [
                    'id'            => $p->id,
                    'name'          => $p->name,
                    'category_id'   => $p->category_id,
                    'category_name' => $p->category->name ?? '-',
                    'status'        => $p->status,
                    'created_at'    => $p->created_at->format('d M Y'),
                ];
            });

        return response()->json($products);
    }

    public function create()
    {
        $categories = DiaryProductCategory::orderBy('name')->get();
        return view('admin.diary-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        DiaryProduct::create([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'status'      => 1,
            'created_by'  => auth()->id(),
        ]);

        return redirect()
            ->route('diary-products.index')
            ->with('success', 'Product added successfully.');
    }

    public function show($id)
    {
        $product = DiaryProduct::with('category')->findOrFail($id);
        return view('admin.diary-products.show', compact('product'));
    }

    public function edit($id)
    {
        $product    = DiaryProduct::findOrFail($id);
        $categories = DiaryProductCategory::orderBy('name')->get();
        return view('admin.diary-products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product   = DiaryProduct::findOrFail($id);
        $validated = $request->validate($this->rules(), $this->messages());

        $product->update([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()
            ->route('diary-products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function toggleStatus($id)
    {
        $product         = DiaryProduct::findOrFail($id);
        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        return response()->json([
            'status'  => $product->status,
            'message' => 'Status updated.',
        ]);
    }

    public function destroy($id)
    {
        DiaryProduct::findOrFail($id)->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}