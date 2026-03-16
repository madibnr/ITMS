<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Incidents</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Laporan insiden dan gangguan layanan IT</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('incidents.export') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('incidents.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Report Incident
            </a>
        </div>
    </div>

    {{-- Incidents Table --}}
    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Reported By</th>
                            <th>Occurred</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incidents as $inc)
                        <tr>
                            <td>
                                <a href="{{ route('incidents.show', $inc) }}" class="link-primary" style="font-weight: 600; font-family: monospace; font-size: 13px;">{{ $inc->incident_number }}</a>
                            </td>
                            <td style="color: var(--text-primary); font-weight: 500;">{{ Str::limit($inc->title, 50) }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($inc->severity) }}">{{ $inc->severity }}</span>
                            </td>
                            <td>
                                <span class="badge {{ in_array($inc->status, ['Open','Investigating']) ? 'badge-open' : ($inc->status === 'Resolved' ? 'badge-resolved' : 'badge-closed') }}">
                                    {{ $inc->status }}
                                </span>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $inc->reporter->name ?? '-' }}</td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $inc->occurred_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">
                                No incidents found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $incidents->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
