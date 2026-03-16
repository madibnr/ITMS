<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Models\License;
use App\Models\Software;
use App\Models\User;
use App\Services\LicenseService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function __construct(private LicenseService $licenseService)
    {
    }

    public function index(Request $request)
    {
        $licenses = $this->licenseService->listLicenses($request->all());
        $software = Software::orderBy('software_name')->get();

        return view('licenses.index', compact('licenses', 'software'));
    }

    public function create()
    {
        $software = Software::orderBy('software_name')->get();
        return view('licenses.create', compact('software'));
    }

    public function store(StoreLicenseRequest $request)
    {
        $this->licenseService->createLicense($request->validated());

        return redirect()->route('licenses.index')
            ->with('success', 'License created successfully.');
    }

    public function edit(License $license)
    {
        $software = Software::orderBy('software_name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();
        $license->load('users');

        return view('licenses.edit', compact('license', 'software', 'users'));
    }

    public function update(UpdateLicenseRequest $request, License $license)
    {
        $this->licenseService->updateLicense($license, $request->validated());

        return redirect()->route('licenses.index')
            ->with('success', 'License updated successfully.');
    }

    public function destroy(License $license)
    {
        $this->licenseService->deleteLicense($license);

        return redirect()->route('licenses.index')
            ->with('success', 'License deleted successfully.');
    }

    public function assignUser(Request $request, License $license)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        try {
            $this->licenseService->assignUser($license, $request->user_id);
            return back()->with('success', 'User assigned to license.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function revokeUser(License $license, User $user)
    {
        $this->licenseService->revokeUser($license, $user->id);
        return back()->with('success', 'User revoked from license.');
    }
}
