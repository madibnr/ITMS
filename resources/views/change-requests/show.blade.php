<x-app-layout>
    <x-slot name="header"><div class="flex justify-between items-center"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $changeRequest->change_number }}</h2>
        <div class="flex gap-2">
            @if($changeRequest->status === 'Draft')<form method="POST" action="{{ route('change-requests.submit', $changeRequest) }}">@csrf @method('PATCH')<button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Submit</button></form>@endif
            @if($changeRequest->status === 'Submitted')<form method="POST" action="{{ route('change-requests.approve', $changeRequest) }}">@csrf @method('PATCH')<button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Approve</button></form><form method="POST" action="{{ route('change-requests.reject', $changeRequest) }}">@csrf @method('PATCH')<button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Reject</button></form>@endif
            @if($changeRequest->status === 'Approved')<form method="POST" action="{{ route('change-requests.implement', $changeRequest) }}">@csrf @method('PATCH')<button class="bg-green-700 text-white px-3 py-1 rounded text-sm hover:bg-green-800">Mark Implemented</button></form>@endif
        </div>
    </div></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold">{{ $changeRequest->title }}</h3>
            <div class="mt-4 space-y-4 text-sm">
                <div><strong class="text-gray-500">Description:</strong><p class="mt-1">{{ $changeRequest->description }}</p></div>
                <div><strong class="text-gray-500">Reason:</strong><p class="mt-1">{{ $changeRequest->reason }}</p></div>
                @if($changeRequest->rollback_plan)<div><strong class="text-gray-500">Rollback Plan:</strong><p class="mt-1">{{ $changeRequest->rollback_plan }}</p></div>@endif
            </div>
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $changeRequest->status }}</span></dd></div>
                <div><dt class="text-gray-500">Impact</dt><dd>{{ $changeRequest->impact }}</dd></div>
                <div><dt class="text-gray-500">Risk Level</dt><dd>{{ $changeRequest->risk_level }}</dd></div>
                <div><dt class="text-gray-500">Requested By</dt><dd>{{ $changeRequest->requester->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Approved By</dt><dd>{{ $changeRequest->approver->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Scheduled</dt><dd>{{ $changeRequest->scheduled_date?->format('d M Y H:i') ?? '-' }}</dd></div>
                @if($changeRequest->implemented_at)<div><dt class="text-gray-500">Implemented</dt><dd>{{ $changeRequest->implemented_at->format('d M Y H:i') }}</dd></div>@endif
            </dl>
        </div>
    </div></div></div>
</x-app-layout>
