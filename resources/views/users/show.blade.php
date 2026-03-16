<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $user->phone ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Department</dt><dd>{{ $user->department ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
            <div><dt class="text-gray-500">Roles</dt><dd>@foreach($user->roles as $r)<span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 mr-1">{{ $r->name }}</span>@endforeach @if($user->roles->isEmpty()) - @endif</dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd>{{ $user->created_at->format('d M Y') }}</dd></div>
        </dl>
        <div class="mt-6"><a href="{{ route('users.edit', $user) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Edit User</a></div>
    </div></div></div>
</x-app-layout>
