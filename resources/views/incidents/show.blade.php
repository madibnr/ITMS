<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $incident->incident_number }}</h2></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">{{ $incident->title }}</h3>
                <div class="prose max-w-none text-gray-700 mt-2">{!! nl2br(e($incident->description)) !!}</div>
                @if($incident->impact_description)<div class="mt-4 p-3 bg-orange-50 rounded"><strong class="text-orange-700">Impact:</strong><p class="text-orange-600 mt-1">{{ $incident->impact_description }}</p></div>@endif
                @if($incident->resolution)<div class="mt-4 p-3 bg-green-50 rounded"><strong class="text-green-700">Resolution:</strong><p class="text-green-600 mt-1">{{ $incident->resolution }}</p></div>@endif
            </div>
            @if($incident->rootCauseAnalysis)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-3">Root Cause Analysis</h3>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-gray-500 font-medium">Root Cause</dt><dd>{{ $incident->rootCauseAnalysis->root_cause }}</dd></div>
                    <div><dt class="text-gray-500 font-medium">Corrective Action</dt><dd>{{ $incident->rootCauseAnalysis->corrective_action }}</dd></div>
                    @if($incident->rootCauseAnalysis->preventive_action)<div><dt class="text-gray-500 font-medium">Preventive Action</dt><dd>{{ $incident->rootCauseAnalysis->preventive_action }}</dd></div>@endif
                    <div><dt class="text-gray-500 font-medium">Analyzed By</dt><dd>{{ $incident->rootCauseAnalysis->analyst->name ?? '-' }}</dd></div>
                </dl>
            </div>
            @endif
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h4 class="font-semibold mb-3">Details</h4>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full @if(in_array($incident->status,['Open','Investigating'])) bg-blue-100 text-blue-800 @elseif($incident->status==='Resolved') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">{{ $incident->status }}</span></dd></div>
                <div><dt class="text-gray-500">Severity</dt><dd><span class="px-2 py-1 text-xs rounded-full @if($incident->severity==='Critical') bg-red-100 text-red-800 @elseif($incident->severity==='High') bg-orange-100 text-orange-800 @else bg-yellow-100 text-yellow-800 @endif">{{ $incident->severity }}</span></dd></div>
                <div><dt class="text-gray-500">Reported By</dt><dd>{{ $incident->reporter->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Assigned To</dt><dd>{{ $incident->assignee->name ?? 'Unassigned' }}</dd></div>
                @if($incident->relatedAsset)<div><dt class="text-gray-500">Related Asset</dt><dd><a href="{{ route('assets.show', $incident->relatedAsset) }}" class="text-indigo-600 hover:underline">{{ $incident->relatedAsset->asset_code }}</a></dd></div>@endif
                @if($incident->relatedTicket)<div><dt class="text-gray-500">Related Ticket</dt><dd><a href="{{ route('tickets.show', $incident->relatedTicket) }}" class="text-indigo-600 hover:underline">{{ $incident->relatedTicket->ticket_number }}</a></dd></div>@endif
                <div><dt class="text-gray-500">Occurred</dt><dd>{{ $incident->occurred_at->format('d M Y H:i') }}</dd></div>
                @if($incident->resolved_at)<div><dt class="text-gray-500">Resolved</dt><dd>{{ $incident->resolved_at->format('d M Y H:i') }}</dd></div>@endif
            </dl>
        </div>
    </div></div></div>
</x-app-layout>
