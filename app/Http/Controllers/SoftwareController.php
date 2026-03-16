<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoftwareRequest;
use App\Http\Requests\UpdateSoftwareRequest;
use App\Models\Software;
use App\Services\LicenseService;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    public function __construct(private LicenseService $licenseService)
    {
    }

    public function index(Request $request)
    {
        $software = $this->licenseService->listSoftware($request->all());
        return view('software.index', compact('software'));
    }

    public function create()
    {
        return view('software.create');
    }

    public function store(StoreSoftwareRequest $request)
    {
        Software::create($request->validated());

        return redirect()->route('software.index')
            ->with('success', 'Software created successfully.');
    }

    public function edit(Software $software)
    {
        return view('software.edit', compact('software'));
    }

    public function update(UpdateSoftwareRequest $request, Software $software)
    {
        $software->update($request->validated());

        return redirect()->route('software.index')
            ->with('success', 'Software updated successfully.');
    }

    public function destroy(Software $software)
    {
        $software->delete();

        return redirect()->route('software.index')
            ->with('success', 'Software deleted successfully.');
    }
}
