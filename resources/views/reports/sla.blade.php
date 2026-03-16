<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">SLA Performance Report</h2></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="grid grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded"><div class="text-3xl font-bold">{{ $sla['total'] }}</div><div class="text-sm text-gray-500">Total Resolved</div></div>
                <div class="text-center p-4 bg-green-50 rounded"><div class="text-3xl font-bold text-green-600">{{ $sla['met_sla'] }}</div><div class="text-sm text-gray-500">Met SLA</div></div>
                <div class="text-center p-4 bg-red-50 rounded"><div class="text-3xl font-bold text-red-600">{{ $sla['breached_sla'] }}</div><div class="text-sm text-gray-500">Breached</div></div>
                <div class="text-center p-4 bg-indigo-50 rounded"><div class="text-3xl font-bold text-indigo-600">{{ $sla['compliance_rate'] }}%</div><div class="text-sm text-gray-500">Compliance Rate</div></div>
            </div>
            <div class="mt-6"><h3 class="font-semibold mb-2">SLA Targets</h3>
                <table class="min-w-full text-sm"><thead><tr><th class="text-left py-2">Priority</th><th class="text-left py-2">Target</th></tr></thead><tbody>
                    <tr><td class="py-1">Critical</td><td>4 hours</td></tr>
                    <tr><td class="py-1">High</td><td>8 hours</td></tr>
                    <tr><td class="py-1">Medium</td><td>24 hours</td></tr>
                    <tr><td class="py-1">Low</td><td>48 hours</td></tr>
                </tbody></table>
            </div>
        </div>
    </div></div>
</x-app-layout>
