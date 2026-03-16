<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Asset Summary Report</h2></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center"><div class="text-3xl font-bold">{{ $summary['total'] }}</div><div class="text-sm text-gray-500">Total Assets</div></div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center"><div class="text-3xl font-bold text-green-600">{{ $summary['active'] }}</div><div class="text-sm text-gray-500">Active</div></div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center"><div class="text-3xl font-bold text-yellow-600">{{ $summary['maintenance'] }}</div><div class="text-sm text-gray-500">Maintenance</div></div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center"><div class="text-3xl font-bold text-gray-400">{{ $summary['retired'] }}</div><div class="text-sm text-gray-500">Retired</div></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6"><h3 class="text-lg font-semibold mb-4">Assets by Category</h3>
                <div class="space-y-3">@foreach($summary['by_category'] as $cat => $count)<div class="flex justify-between items-center"><span class="text-gray-700">{{ $cat }}</span><span class="font-bold">{{ $count }}</span></div>@endforeach
                @if(empty($summary['by_category']))<p class="text-gray-500 text-sm">No data.</p>@endif</div>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6"><h3 class="text-lg font-semibold mb-4">Alerts</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded"><span class="text-orange-700">Warranty Expiring (30 days)</span><span class="font-bold text-orange-600">{{ $summary['warranty_expiring'] }}</span></div>
                </div>
            </div>
        </div>
    </div></div>
</x-app-layout>
