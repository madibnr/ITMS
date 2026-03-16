<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Maintenance Schedule</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('maintenance.store') }}">@csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Title</label><input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Maintenance Type</label><select name="maintenance_type" class="mt-1 block w-full rounded-md border-gray-300" required>@foreach(['Preventive','Corrective','Emergency'] as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Frequency</label><select name="frequency" class="mt-1 block w-full rounded-md border-gray-300"><option value="">None</option>@foreach(['Daily','Weekly','Monthly','Quarterly','Yearly','One-time'] as $f)<option value="{{ $f }}">{{ $f }}</option>@endforeach</select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Scheduled Date</label><input type="datetime-local" name="scheduled_date" value="{{ old('scheduled_date') }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Asset</label><select name="asset_id" class="mt-1 block w-full rounded-md border-gray-300"><option value="">None</option>@foreach($assets as $a)<option value="{{ $a->id }}">{{ $a->asset_code }} - {{ $a->name }}</option>@endforeach</select></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Assign To</label><select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300"><option value="">Unassigned</option>@foreach($technicians as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700">Notes</label><textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('notes') }}</textarea></div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('maintenance.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Create Schedule</button>
                </div>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
