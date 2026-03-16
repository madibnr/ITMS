<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $schedule->title }}</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <dl class="space-y-4 text-sm">
            <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full @if($schedule->status==='Completed') bg-green-100 text-green-800 @elseif($schedule->status==='Cancelled') bg-gray-100 text-gray-800 @else bg-blue-100 text-blue-800 @endif">{{ $schedule->status }}</span></dd></div>
            <div><dt class="text-gray-500">Type</dt><dd>{{ $schedule->maintenance_type }}</dd></div>
            <div><dt class="text-gray-500">Frequency</dt><dd>{{ $schedule->frequency ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Scheduled Date</dt><dd>{{ $schedule->scheduled_date->format('d M Y H:i') }}</dd></div>
            @if($schedule->completed_date)<div><dt class="text-gray-500">Completed</dt><dd>{{ $schedule->completed_date->format('d M Y H:i') }}</dd></div>@endif
            <div><dt class="text-gray-500">Asset</dt><dd>{{ $schedule->asset ? $schedule->asset->asset_code . ' - ' . $schedule->asset->name : '-' }}</dd></div>
            <div><dt class="text-gray-500">Assigned To</dt><dd>{{ $schedule->assignee->name ?? '-' }}</dd></div>
            @if($schedule->description)<div><dt class="text-gray-500">Description</dt><dd>{{ $schedule->description }}</dd></div>@endif
            @if($schedule->notes)<div><dt class="text-gray-500">Notes</dt><dd>{{ $schedule->notes }}</dd></div>@endif
        </dl>
        @if($schedule->status === 'Scheduled' || $schedule->status === 'In Progress')
        <div class="mt-6 flex gap-2">
            <form method="POST" action="{{ route('maintenance.complete', $schedule) }}">@csrf @method('PATCH')<button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Mark Complete</button></form>
            <a href="{{ route('maintenance.edit', $schedule) }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">Edit</a>
        </div>
        @endif
    </div></div></div>
</x-app-layout>
