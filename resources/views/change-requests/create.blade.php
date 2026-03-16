<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Change Request</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('change-requests.store') }}">@csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700">Title</label><input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300" required></div>
                <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300" required>{{ old('description') }}</textarea></div>
                <div><label class="block text-sm font-medium text-gray-700">Reason</label><textarea name="reason" rows="2" class="mt-1 block w-full rounded-md border-gray-300" required>{{ old('reason') }}</textarea></div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Impact</label><select name="impact" class="mt-1 block w-full rounded-md border-gray-300" required>@foreach(['Low','Medium','High'] as $i)<option value="{{ $i }}" {{ old('impact','Low')==$i?'selected':'' }}>{{ $i }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Risk Level</label><select name="risk_level" class="mt-1 block w-full rounded-md border-gray-300" required>@foreach(['Low','Medium','High'] as $r)<option value="{{ $r }}" {{ old('risk_level','Low')==$r?'selected':'' }}>{{ $r }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Scheduled Date</label><input type="datetime-local" name="scheduled_date" value="{{ old('scheduled_date') }}" class="mt-1 block w-full rounded-md border-gray-300"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Rollback Plan</label><textarea name="rollback_plan" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('rollback_plan') }}</textarea></div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('change-requests.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Create Change Request</button>
                </div>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
