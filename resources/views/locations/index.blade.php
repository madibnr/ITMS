<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Locations</h1>
        <a href="{{ route('locations.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">+ New Location</a>
    </div>
    <div class="panel"><div class="panel-body" style="padding: 0;"><table class="data-table"><thead><tr><th>Name</th><th>Type</th><th>Parent</th><th>Assets</th><th>Action</th></tr></thead><tbody>
        @forelse($locations as $loc)
        <tr>
            <td style="font-weight: 600;">{{ $loc->name }}</td>
            <td><span class="badge badge-open" style="text-transform: capitalize;">{{ $loc->type }}</span></td>
            <td>{{ $loc->parent?->name ?? '-' }}</td>
            <td>{{ $loc->assets_count }}</td>
            <td style="white-space: nowrap;">
                <a href="{{ route('locations.edit', $loc) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                <form method="POST" action="{{ route('locations.destroy', $loc) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
            </td>
        </tr>
        @if($loc->children->count())
            @foreach($loc->children as $child)
            <tr style="background: var(--hover-bg);">
                <td style="padding-left: 32px;">↳ {{ $child->name }}</td>
                <td><span class="badge badge-open" style="text-transform: capitalize;">{{ $child->type }}</span></td>
                <td>{{ $loc->name }}</td>
                <td>{{ $child->assets_count ?? 0 }}</td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('locations.edit', $child) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                    <form method="POST" action="{{ route('locations.destroy', $child) }}" style="display: inline;">@csrf @method('DELETE')<button onclick="return confirm('Delete?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button></form>
                </td>
            </tr>
            @endforeach
        @endif
        @empty <tr><td colspan="5" style="text-align:center; color: var(--text-muted); padding: 40px;">No locations found.</td></tr>
        @endforelse
    </tbody></table>{{ $locations->links() }}</div></div>
</x-app-layout>
