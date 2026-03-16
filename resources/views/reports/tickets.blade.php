<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Ticket Report</h2></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">SLA Performance</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded text-center"><div class="text-2xl font-bold">{{ $slaPerformance['total'] }}</div><div class="text-sm text-gray-500">Total Resolved</div></div>
                <div class="bg-green-50 p-4 rounded text-center"><div class="text-2xl font-bold text-green-600">{{ $slaPerformance['met_sla'] }}</div><div class="text-sm text-gray-500">Met SLA</div></div>
                <div class="bg-red-50 p-4 rounded text-center"><div class="text-2xl font-bold text-red-600">{{ $slaPerformance['breached_sla'] }}</div><div class="text-sm text-gray-500">Breached SLA</div></div>
                <div class="bg-indigo-50 p-4 rounded text-center"><div class="text-2xl font-bold text-indigo-600">{{ $slaPerformance['compliance_rate'] }}%</div><div class="text-sm text-gray-500">Compliance</div></div>
            </div>
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('reports.tickets') }}" class="flex flex-wrap gap-4">
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="rounded-md border-gray-300 text-sm" placeholder="From">
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="rounded-md border-gray-300 text-sm" placeholder="To">
                <select name="priority" class="rounded-md border-gray-300 text-sm"><option value="">All Priority</option>@foreach(['Low','Medium','High','Critical'] as $p)<option value="{{ $p }}" {{ ($filters['priority'] ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>@endforeach</select>
                <select name="status" class="rounded-md border-gray-300 text-sm"><option value="">All Status</option>@foreach(['Open','In Progress','Resolved','Closed'] as $s)<option value="{{ $s }}" {{ ($filters['status'] ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">Filter</button>
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SLA Met</th>
            </tr></thead><tbody class="divide-y divide-gray-200">
                @forelse($tickets as $t)
                <tr><td class="px-4 py-3 font-mono text-sm">{{ $t->ticket_number }}</td>
                    <td class="px-4 py-3 text-sm">{{ Str::limit($t->title, 40) }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full @if($t->priority==='Critical') bg-red-100 text-red-800 @elseif($t->priority==='High') bg-orange-100 text-orange-800 @else bg-yellow-100 text-yellow-800 @endif">{{ $t->priority }}</span></td>
                    <td class="px-4 py-3 text-sm">{{ $t->status }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $t->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">@if($t->resolved_at && $t->sla_deadline)<span class="{{ $t->resolved_at <= $t->sla_deadline ? 'text-green-600' : 'text-red-600' }}">{{ $t->resolved_at <= $t->sla_deadline ? '✓' : '✗' }}</span>@else - @endif</td>
                </tr>
                @empty <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No tickets found.</td></tr> @endforelse
            </tbody></table>
            <div class="p-4">{{ $tickets->withQueryString()->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
