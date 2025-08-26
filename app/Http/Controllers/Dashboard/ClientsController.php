<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller {
    public function index(Request $request) {
        $query = Client::query()->with(['carModel']);

        if ($request->filter['search'] ?? null) {
            $query
                ->where('name', 'like', '%' . $request->filter['search'] . '%')
                ->orWhere('mobile', 'like', '%' . $request->filter['search'] . '%')
                ->orWhere('email', 'like', '%' . $request->filter['search'] . '%');
        }
        if ($request->filter['car_model_id'] ?? null) {
            $query->where('car_model_id', $request->filter['car_model_id']);
        }

        $clients = $query->orderBy('name')->get();

        return inertia('admin/Clients/Index', [
            'clients' => $clients,
            'filters' => $request->filter ?? [],
            'car_models' => CarModel::all()
        ]);
    }

    public function create() {
        return inertia('admin/Clients/Create', ['car_models' => CarModel::all()]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:255|unique:clients',
            'email' => 'nullable|string|email|max:255|unique:clients',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'تم إنشاء العميل بنجاح.');
    }

    public function show(Client $client) {
        $client->load(['carModel']);
        return response()->json(['client' => $client]);
    }

    public function edit(Client $client) {
        $client->load(['carModel']);
        return inertia('admin/Clients/Edit', [
            'client' => $client,
            'car_models' => CarModel::all(),
        ]);
    }

    public function update(Request $request, Client $client) {

        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255|unique:clients,mobile,' . $client->id,
            'email' => 'nullable|string|email|max:255|unique:clients,email,' . $client->id,
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function destroy(Client $client) {
        $client->delete();
        return back()->with('success', 'تم حذف العميل بنجاح.');
    }
}
