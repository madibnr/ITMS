<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Documentation Tags</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage tags for filtering documentation</p>
        </div>
        @can('create', \App\Models\DocumentationTag::class)
        <a href="{{ route('doc-tags.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Tag
        </a>
        @endcan
    </div>

    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tag Name</th>
                            <th>Slug</th>
                            <th>Documents</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                        <tr>
                            <td>
                                <span class="badge badge-open" style="font-size: 12px; padding: 4px 10px;">🏷 {{ $tag->name }}</span>
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted);">{{ $tag->slug }}</td>
                            <td>
                                <a href="{{ route('docs.index', ['tag_id' => $tag->id]) }}" class="link-primary" style="font-weight: 600;">{{ $tag->documentations_count }}</a>
                            </td>
                            <td style="font-size: 13px; color: var(--text-muted);">{{ $tag->created_at->format('d M Y') }}</td>
                            <td style="white-space: nowrap;">
                                @can('update', $tag)
                                <a href="{{ route('doc-tags.edit', $tag) }}" style="color: #64748b; font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid var(--border-color); border-radius: 6px; margin-right: 4px;">Edit</a>
                                @endcan
                                @can('delete', $tag)
                                <form method="POST" action="{{ route('doc-tags.destroy', $tag) }}" style="display: inline;" onsubmit="return confirm('Delete this tag?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">No tags yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 16px;">
        <a href="{{ route('docs.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">← Back to Documentation</a>
    </div>
</x-app-layout>
