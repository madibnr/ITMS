<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::roots()
            ->with('childrenRecursive')
            ->withCount('assets')
            ->when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->orderBy('name')
            ->paginate(20);

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        $parents = Location::orderBy('name')->get();
        return view('locations.create', compact('parents'));
    }

    public function store(StoreLocationRequest $request)
    {
        Location::create($request->validated());

        return redirect()->route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        $parents = Location::where('id', '!=', $location->id)->orderBy('name')->get();
        return view('locations.edit', compact('location', 'parents'));
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}
