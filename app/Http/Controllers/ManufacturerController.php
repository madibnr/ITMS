<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $manufacturers = Manufacturer::withCount('assetModels')
            ->when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest()
            ->paginate(15);

        return view('manufacturers.index', compact('manufacturers'));
    }

    public function create()
    {
        return view('manufacturers.create');
    }

    public function store(StoreManufacturerRequest $request)
    {
        Manufacturer::create($request->validated());

        return redirect()->route('manufacturers.index')
            ->with('success', 'Manufacturer created successfully.');
    }

    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($request->validated());

        return redirect()->route('manufacturers.index')
            ->with('success', 'Manufacturer updated successfully.');
    }

    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();

        return redirect()->route('manufacturers.index')
            ->with('success', 'Manufacturer deleted successfully.');
    }
}
