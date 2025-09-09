<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Labour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaboursController extends Controller {
    public function index(Request $request) {
        $query = Labour::query();

        // Search filter
        if ($request->filter['search'] ?? null) {
            $query->where('name_ar', 'like', '%' . $request->filter['search'] . '%')
                ->orWhere('name_en', 'like', '%' . $request->filter['search'] . '%');
        }

        // Filter by active status
        if ($request->filter['is_active'] ?? null) {
            $query->where('is_active', $request->filter['is_active'] === '1');
        }

        $labours = $query->orderBy('name_ar')->get();

        return Inertia::render('Labours/Index', [
            'labours' => $labours,
            'filters' => $request->filter ?? [],
        ]);
    }

    public function create() {
        return Inertia::render('Labours/Create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:labours',
            'name_en' => 'nullable|string|max:255',
            'base_fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Labour::create($validated);

        return redirect()->route('labours.index')->with('success', 'تم إنشاء الخدمة بنجاح.');
    }

    public function edit(Labour $labour) {
        return Inertia::render('Labours/Edit', [
            'labour' => $labour,
        ]);
    }

    public function update(Request $request, Labour $labour) {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:labours,name_ar,' . $labour->id,
            'name_en' => 'nullable|string|max:255',
            'base_fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $labour->update($validated);

        return redirect()->route('labours.index')->with('success', 'تم تحديث الخدمة بنجاح.');
    }

    public function destroy(Labour $labour) {
        $labour->delete();
        return back()->with('success', 'تم حذف الخدمة بنجاح.');
    }

    public function show($id) {
        $labour = Labour::withTrashed()->findOrFail($id);
        return response()->json($labour);
    }
}
