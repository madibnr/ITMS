<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Incident: {{ $incident->incident_number }}</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('incidents.update', $incident) }}">@csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Title</label><input type="text" name="title" value="{{ old('title', $incident->title) }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300" required>{{ old('description', $incident->description) }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Severity</label><select name="severity" class="mt-1 block w-full rounded-md border-gray-300">@foreach(['Low','Medium','High','Critical'] as $s)<option value="{{ $s }}" {{ old('severity', $incident->severity)==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Status</label><select name="status" class="mt-1 block w-full rounded-md border-gray-300">@foreach(['Open','Investigating','Resolved','Closed'] as $s)<option value="{{ $s }}" {{ old('status', $incident->status)==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Assigned To</label><select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300"><option value="">Unassigned</option>@foreach($technicians as $t)<option value="{{ $t->id }}" {{ old('assigned_to', $incident->assigned_to)==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700">Resolution</label><textarea name="resolution" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('resolution', $incident->resolution) }}</textarea></div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('incidents.show', $incident) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Update Incident</button>
                </div>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
