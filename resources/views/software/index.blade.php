<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Software</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('licenses.index') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px;">View Licenses</a>
            <a href="{{ route('software.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">+ New Software</a>
        </div>
    </div>
    <div class="panel"><div class="panel-body" style="padding: 0;"><table class="data-table"><thead><tr><th>Software</th><th>Vendor</th><th>Version</th><th>Category</th><th>Licenses</th><th>Action</th></tr></thead><tbody>
        @forelse($software as $s)
        <tr>
            <td style="font-weight: 600;">{{ $s->software_name }}</td>
            <td>{{ $s->vendor ?? '-' }}</td>
            <td>{{ $s->version ?? '-' }}</td>
            <td>{{ $s->category ?? '-' }}</td>
            <td><span class="badge badge-open">{{ $s->licenses_count }}</span></td>
            <td style="white-space: nowrap;">
                <a href="{{ route('software.edit', $s) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                <form method="POST" action="{{ route('software.destroy', $s) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
            </td>
        </tr>
        @empty <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 40px;">No software found.</td></tr>
        @endforelse
    </tbody></table>{{ $software->links() }}</div></div>
</x-app-layout>
