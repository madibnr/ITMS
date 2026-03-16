<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User: {{ $user->name }}</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Name</label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">New Password (leave blank to keep)</label><input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Confirm Password</label><input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Phone</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border-gray-300"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Department</label><input type="text" name="department" value="{{ old('department', $user->department) }}" class="mt-1 block w-full rounded-md border-gray-300"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                    <div class="flex flex-wrap gap-4">@foreach($roles as $role)
                        <label class="flex items-center text-sm"><input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-gray-300 mr-2" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}> {{ $role->name }}</label>
                    @endforeach</div>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('users.show', $user) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Update User</button>
                </div>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
