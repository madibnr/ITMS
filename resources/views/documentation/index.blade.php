<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">IT Documentation</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Centralized knowledge base for the IT team</p>
        </div>
        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->hasAnyRole(['admin','manager','it-staff']))
            <a href="{{ route('docs.export', request()->query()) }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Export Excel
            </a>
            @can('create', \App\Models\Documentation::class)
            <a href="{{ route('docs.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Document
            </a>
            @endcan
            @endif
        </div>
    </div>

    {{-- Stats & Recent --}}
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 16px; margin-bottom: 20px;">
        <div class="panel" style="padding: 20px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Published Docs</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--primary);">{{ $totalCount }}</div>
            @if(auth()->user()->hasRole('admin'))
            <div style="margin-top: 16px; display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('doc-categories.index') }}" style="font-size: 12px; color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 5px;">
                    📂 Manage Categories
                </a>
                <a href="{{ route('doc-tags.index') }}" style="font-size: 12px; color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 5px;">
                    🏷 Manage Tags
                </a>
            </div>
            @elseif(auth()->user()->hasRole('manager'))
            <div style="margin-top: 16px;">
                <a href="{{ route('doc-tags.index') }}" style="font-size: 12px; color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 5px;">
                    🏷 Manage Tags
                </a>
            </div>
            @endif
        </div>
        <div class="panel" style="padding: 20px;">
            <div style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 12px;">Recently Updated</div>
            @forelse($recentDocs as $recent)
            <a href="{{ route('docs.show', $recent) }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border-color); text-decoration: none; color: inherit;">
                <span style="font-size: 18px; width: 28px; text-align: center;">{{ $recent->category->icon ?? '📄' }}</span>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-primary);">{{ $recent->title }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">{{ $recent->category->name ?? '-' }} · {{ $recent->updated_at->diffForHumans() }}</div>
                </div>
                <span class="badge {{ $recent->status === 'published' ? 'badge-active' : 'badge-open' }}" style="font-size: 10px;">{{ ucfirst($recent->status) }}</span>
            </a>
            @empty
            <p style="color: var(--text-muted); font-size: 13px;">No documents yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel" style="margin-bottom: 16px;">
        <div class="panel-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('docs.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, category, or tag…" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; width: 220px; outline: none;">
                <select name="category_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="tag_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
                @if(auth()->user()->hasAnyRole(['admin','manager','it-staff']))
                <select name="status" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @endif
                <button type="submit" style="background: var(--text-primary); color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Filter</button>
                @if(request()->hasAny(['search','category_id','tag_id','status']))
                <a href="{{ route('docs.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Documents Table --}}
    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Updated By</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentations as $doc)
                        <tr>
                            <td>
                                <a href="{{ route('docs.show', $doc) }}" class="link-primary" style="font-weight: 500;">{{ $doc->title }}</a>
                                @if($doc->description)
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ Str::limit($doc->description, 60) }}</div>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); white-space: nowrap;">{{ $doc->category->icon ?? '' }} {{ $doc->category->name ?? '-' }}</td>
                            <td>
                                @foreach($doc->tags->take(3) as $tag)
                                <span class="badge badge-open" style="font-size: 10px; margin-right: 2px;">{{ $tag->name }}</span>
                                @endforeach
                                @if($doc->tags->count() > 3)
                                <span style="font-size: 11px; color: var(--text-muted);">+{{ $doc->tags->count() - 3 }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $doc->status === 'published' ? 'badge-active' : 'badge-open' }}">{{ ucfirst($doc->status) }}</span>
                            </td>
                            <td style="color: var(--text-secondary); font-size: 13px;">{{ $doc->updater->name ?? $doc->creator->name ?? '-' }}</td>
                            <td style="color: var(--text-muted); font-size: 13px; white-space: nowrap;">{{ $doc->updated_at->format('d M Y') }}</td>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('docs.show', $doc) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">View</a>
                                @can('update', $doc)
                                <a href="{{ route('docs.edit', $doc) }}" style="color: #64748b; font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid var(--border-color); border-radius: 6px; margin-right: 4px;">Edit</a>
                                @endcan
                                @can('delete', $doc)
                                <form method="POST" action="{{ route('docs.destroy', $doc) }}" style="display: inline;" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; cursor: pointer;">Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">No documents found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $documentations->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
