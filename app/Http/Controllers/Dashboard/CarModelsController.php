<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelsController extends Controller {
    public function index(Request $request) {
        $query = CarModel::query();

        // if ($request->filter['year_from'] ?? null) {
        //     $query->json('year_range', (int) $request->filter['year_from'], 'from');
        // }
        // if ($request->filter['year_to'] ?? null) {
        //     $query->whereJsonLessThan('year_range', (int) $request->filter['year_to'], 'to');
        // }
        // dump($request->filter['year_from'] ?? null, $request->filter['year_to'] ?? null);

        $carModels = $query->orderBy('name')->get()->map(function ($model) {
            $range = $model->year_range;
            $model->year_range_formatted = $range['from'] . ' - ' . $range['to'];
            return $model;
        });

        return inertia('admin/CarModels/Index', [
            'car_models' => $carModels,
            'filters' => $request->filter ?? [],
        ]);
    }

    public function create() {
        return inertia('admin/CarModels/Create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year_range.from' => 'required|integer|min:1900|max:2100',
            'year_range.to' => 'required|integer|gte:year_range.from|min:1900|max:2100',
        ]);
        CarModel::create([
            'name' => $request->name,
            'year_range' => [
                'from' => $request->year_range['from'],
                'to' => $request->year_range['to'],
            ],
        ]);

        return redirect()->route('car-models.index')->with('success', 'تم إنشاء الموديل بنجاح.');
    }

    public function show(CarModel $carModel) {
        return response()->json($carModel);
    }

    public function edit(CarModel $carModel) {
        return inertia('admin/CarModels/Edit', [
            'car_model' => $carModel,
        ]);
    }

    public function update(Request $request, CarModel $carModel) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year_range.from' => 'required|integer|min:1900|max:2100',
            'year_range.to' => 'required|integer|gte:year_range.from|min:1900|max:2100',
        ]);

        $carModel->update([
            'name' => $request->name,
            'year_range' => [
                'from' => $request->year_range['from'],
                'to' => $request->year_range['to'],
            ],
        ]);

        return redirect()->route('car-models.index')->with('success', 'تم تحديث الموديل بنجاح.');
    }

    public function destroy(CarModel $carModel) {
        $carModel->delete();
        return back()->with('success', 'تم حذف الموديل بنجاح.');
    }
}
