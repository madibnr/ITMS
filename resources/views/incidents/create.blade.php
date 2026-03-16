<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Incident</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('incidents.store') }}">@csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Title</label><input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300" required>{{ old('description') }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Severity</label><select name="severity" class="mt-1 block w-full rounded-md border-gray-300" required>@foreach(['Low','Medium','High','Critical'] as $s)<option value="{{ $s }}" {{ old('severity','Medium')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Occurred At</label><input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Related Asset</label><select name="related_asset_id" class="mt-1 block w-full rounded-md border-gray-300"><option value="">None</option>@foreach($assets as $a)<option value="{{ $a->id }}">{{ $a->asset_code }} - {{ $a->name }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Related Ticket</label><select name="related_ticket_id" class="mt-1 block w-full rounded-md border-gray-300"><option value="">None</option>@foreach($tickets as $t)<option value="{{ $t->id }}">{{ $t->ticket_number }} - {{ $t->title }}</option>@endforeach</select></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Impact Description</label><textarea name="impact_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('impact_description') }}</textarea></div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('incidents.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Report Incident</button>
                </div>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
