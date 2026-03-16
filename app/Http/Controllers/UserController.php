<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private
        UserService $userService,
        )
    {
    }

    public function index(Request $request)
    {
        $users = $this->userService->list($request->all());
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->create($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function toggleActive(User $user)
    {
        $this->userService->toggleActive($user);
        return redirect()->route('users.index')->with('success', 'User status updated.');
    }

    public function export(Request $request)
    {
        return (new \App\Exports\UsersExport($request->all()))
            ->download('users-export-' . now()->format('Y-m-d') . '.xlsx');
    }
}
