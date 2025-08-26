<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller {
    public function index(Request $request) {
        $query = Category::query();

        // Simple search filter
        if ($request->filter['search'] ?? null) {
            $query->where('name', 'like', '%' . $request->filter['search'] . '%');
        }

        $categories = $query->orderBy('name')->get();

        return inertia('admin/Categories/Index', [
            'categories' => $categories,
            'filters' => $request->filter ?? [],
        ]);
    }

    public function create() {
        return inertia('admin/Categories/Create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:255',
        ]);
        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'تم إنشاء الفئة بنجاح.');
    }

    public function show(Category $category) {
        return response()->json($category);
    }

    public function edit(Category $category) {
        return inertia('admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'تم تحديث الفئة بنجاح.');
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('success', 'تم حذف الفئة بنجاح.');
    }
}
