<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Licenses</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('software.index') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px;">View Software</a>
            <a href="{{ route('licenses.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">+ New License</a>
        </div>
    </div>
    <div class="panel"><div class="panel-body" style="padding: 0;"><table class="data-table"><thead><tr><th>Software</th><th>License Key</th><th>Seats</th><th>Used</th><th>Expiration</th><th>Action</th></tr></thead><tbody>
        @forelse($licenses as $l)
        <tr>
            <td style="font-weight: 600;">{{ $l->software->software_name ?? '-' }}</td>
            <td style="font-family: monospace; font-size: 13px;">{{ Str::limit($l->license_key, 30) }}</td>
            <td>{{ $l->seats }}</td>
            <td>{{ $l->users->count() }} / {{ $l->seats }}</td>
            <td>
                @if($l->isExpired()) <span class="badge badge-closed">Expired {{ $l->expiration_date->format('d M Y') }}</span>
                @elseif($l->isExpiringSoon()) <span class="badge badge-in-progress">{{ $l->expiration_date->format('d M Y') }}</span>
                @elseif($l->expiration_date) {{ $l->expiration_date->format('d M Y') }}
                @else - @endif
            </td>
            <td style="white-space: nowrap;">
                <a href="{{ route('licenses.edit', $l) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                <form method="POST" action="{{ route('licenses.destroy', $l) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
            </td>
        </tr>
        @empty <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 40px;">No licenses found.</td></tr>
        @endforelse
    </tbody></table>{{ $licenses->links() }}</div></div>
</x-app-layout>
