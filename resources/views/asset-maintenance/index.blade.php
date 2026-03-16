<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Asset Maintenance</h1>
        <a href="{{ route('asset-maintenance.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">+ New Record</a>
    </div>
    <div class="panel"><div class="panel-body" style="padding: 0;"><table class="data-table"><thead><tr><th>Asset</th><th>Type</th><th>Vendor</th><th>Cost</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr></thead><tbody>
        @forelse($maintenance as $m)
        <tr>
            <td><a href="{{ route('assets.show', $m->asset_id) }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">{{ $m->asset->name ?? '-' }}</a></td>
            <td>{{ $m->maintenance_type }}</td>
            <td>{{ $m->vendor ?? '-' }}</td>
            <td>{{ $m->cost ? 'Rp ' . number_format($m->cost, 0, ',', '.') : '-' }}</td>
            <td>{{ $m->start_date->format('d M Y') }}</td>
            <td>{{ $m->end_date?->format('d M Y') ?? '-' }}</td>
            <td>@php $mc = match($m->status) { 'Completed' => 'badge-active', 'In Progress' => 'badge-in-progress', default => 'badge-open' }; @endphp <span class="badge {{ $mc }}">{{ $m->status }}</span></td>
            <td style="white-space: nowrap;">
                <a href="{{ route('asset-maintenance.edit', $m) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                <form method="POST" action="{{ route('asset-maintenance.destroy', $m) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
            </td>
        </tr>
        @empty <tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 40px;">No maintenance records.</td></tr>
        @endforelse
    </tbody></table>{{ $maintenance->links() }}</div></div>
</x-app-layout>
