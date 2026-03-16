<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Documentation Categories</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage documentation categories</p>
        </div>
        @can('create', \App\Models\DocumentationCategory::class)
        <a href="{{ route('doc-categories.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Category
        </a>
        @endcan
    </div>

    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td style="font-size: 22px; text-align: center; width: 50px;">{{ $category->icon ?? '📄' }}</td>
                            <td>
                                <div style="font-weight: 500; color: var(--text-primary);">{{ $category->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $category->slug }}</div>
                            </td>
                            <td style="font-size: 13px; color: var(--text-secondary);">{{ $category->description ?: '—' }}</td>
                            <td>
                                <a href="{{ route('docs.index', ['category_id' => $category->id]) }}" class="link-primary" style="font-weight: 600;">{{ $category->documentations_count }}</a>
                            </td>
                            <td style="white-space: nowrap;">
                                @can('update', $category)
                                <a href="{{ route('doc-categories.edit', $category) }}" style="color: #64748b; font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid var(--border-color); border-radius: 6px; margin-right: 4px;">Edit</a>
                                @endcan
                                @can('delete', $category)
                                @if($category->documentations_count == 0)
                                <form method="POST" action="{{ route('doc-categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button>
                                </form>
                                @else
                                <span style="color: var(--text-muted); font-size: 12px;">Has documents</span>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">No categories yet.</td>
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
