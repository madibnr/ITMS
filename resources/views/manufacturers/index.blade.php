<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Manufacturers</h1>
        <a href="{{ route('manufacturers.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">+ New Manufacturer</a>
    </div>
    <div class="panel"><div class="panel-body" style="padding: 0;"><table class="data-table"><thead><tr><th>Name</th><th>Website</th><th>Support Email</th><th>Models</th><th>Action</th></tr></thead><tbody>
        @forelse($manufacturers as $m)
        <tr>
            <td style="font-weight: 600;">{{ $m->name }}</td>
            <td>@if($m->website)<a href="{{ $m->website }}" target="_blank" style="color: var(--primary);">{{ $m->website }}</a>@else - @endif</td>
            <td>{{ $m->support_email ?? '-' }}</td>
            <td><span class="badge badge-open">{{ $m->asset_models_count }}</span></td>
            <td style="white-space: nowrap;">
                <a href="{{ route('manufacturers.edit', $m) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                <form method="POST" action="{{ route('manufacturers.destroy', $m) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
            </td>
        </tr>
        @empty <tr><td colspan="5" style="text-align:center; color: var(--text-muted); padding: 40px;">No manufacturers found.</td></tr>
        @endforelse
    </tbody></table>{{ $manufacturers->links() }}</div></div>
</x-app-layout>
