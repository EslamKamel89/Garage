<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller {
    public function index(Request $request) {
        $query = Product::with('category');

        if ($request->filter['search'] ?? null) {
            $search = $request->filter['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        if ($request->filter['category_id'] ?? null) {
            $query->where('category_id', $request->filter['category_id']);
        }

        $products = $query->orderBy('name')->get();

        return inertia('admin/Products/Index', [
            'products' => $products,
            'filters' => $request->filter ?? [],
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create() {
        return inertia('admin/Products/Create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'nullable|string|max:255|unique:products',
            'name' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'تم إنشاء المنتج بنجاح.');
    }

    public function show(Product $product) {
        return response()->json($product);
    }

    public function edit(Product $product) {
        return inertia('admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product) {
        $product->delete();
        return back()->with('success', 'تم حذف المنتج بنجاح.');
    }
}
