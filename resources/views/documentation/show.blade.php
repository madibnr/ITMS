<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">{{ $documentation->title }}</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                <a href="{{ route('docs.index') }}" style="color: var(--text-muted); text-decoration: none;">IT Documentation</a>
                / {{ $documentation->category->name ?? 'General' }}
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-shrink: 0;">
            <span class="badge {{ $documentation->status === 'published' ? 'badge-active' : 'badge-open' }}" style="padding: 6px 12px;">{{ ucfirst($documentation->status) }}</span>
            @if($documentation->attachment)
            <a href="{{ route('docs.download', $documentation) }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download
            </a>
            @endif
            @can('update', $documentation)
            <a href="{{ route('docs.edit', $documentation) }}" style="color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; padding: 8px 16px; border: 1px solid var(--border-color); border-radius: 8px;">Edit</a>
            @endcan
            @can('delete', $documentation)
            <form method="POST" action="{{ route('docs.destroy', $documentation) }}" style="display: inline;" onsubmit="return confirm('Delete this document permanently?')">
                @csrf @method('DELETE')
                <button type="submit" style="color: #ef4444; font-size: 13px; font-weight: 600; background: none; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 16px; cursor: pointer;">Delete</button>
            </form>
            @endcan
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 280px; gap: 20px;">
        {{-- Main Content --}}
        <div style="display: flex; flex-direction: column; gap: 16px;">
            {{-- Description --}}
            @if($documentation->description)
            <div class="panel" style="padding: 16px 20px; border-left: 4px solid var(--primary);">
                <p style="margin: 0; color: var(--text-secondary); font-size: 14px;">{{ $documentation->description }}</p>
            </div>
            @endif

            {{-- Tags --}}
            @if($documentation->tags->count())
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                @foreach($documentation->tags as $tag)
                <a href="{{ route('docs.index', ['tag_id' => $tag->id]) }}" class="badge badge-open" style="text-decoration: none; font-size: 12px; padding: 4px 10px;">
                    🏷 {{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Document Body --}}
            <div class="panel">
                <div class="panel-body" style="padding: 28px;">
                    <div class="doc-content" style="line-height: 1.7; font-size: 15px; color: var(--text-primary);">
                        {!! $documentation->content !!}
                    </div>
                </div>
            </div>

            {{-- Structured Meta --}}
            @if($documentation->meta->count())
            <div class="panel">
                <div class="panel-body" style="padding: 0;">
                    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
                        <h3 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0;">📋 Structured Information</h3>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <tbody>
                                @foreach($documentation->meta as $meta)
                                <tr>
                                    <td style="font-weight: 600; width: 200px; background: var(--bg-secondary, #f8fafc); font-size: 13px; color: var(--text-secondary);">{{ ucwords(str_replace('_', ' ', $meta->key)) }}</td>
                                    <td style="white-space: pre-wrap; font-size: 14px;">{{ $meta->value ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div class="panel">
                <div class="panel-body" style="padding: 20px;">
                    <h4 style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Document Details</h4>
                    <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13px;">
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;">Category</div>
                            <div style="color: var(--text-primary);">{{ $documentation->category->icon ?? '' }} {{ $documentation->category->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;">Created By</div>
                            <div style="color: var(--text-primary);">{{ $documentation->creator->name ?? '—' }}</div>
                            <div style="color: var(--text-muted); font-size: 11px;">{{ $documentation->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        @if($documentation->updater)
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;">Last Updated By</div>
                            <div style="color: var(--text-primary);">{{ $documentation->updater->name }}</div>
                            <div style="color: var(--text-muted); font-size: 11px;">{{ $documentation->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                        @endif
                        @if($documentation->attachment)
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Attachment</div>
                            <a href="{{ route('docs.download', $documentation) }}" style="color: var(--primary); font-size: 12px; text-decoration: none; word-break: break-all;">
                                📎 {{ $documentation->attachment_original_name ?? basename($documentation->attachment) }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('docs.index') }}" style="display: block; text-align: center; padding: 10px 20px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">← Back to Documentation</a>
        </div>
    </div>

    <style>
        .doc-content h1,.doc-content h2,.doc-content h3 { margin-top: 1.2em; margin-bottom: .5em; font-weight: 600; }
        .doc-content p { margin-bottom: .8em; }
        .doc-content ul,.doc-content ol { padding-left: 1.5em; margin-bottom: .8em; }
        .doc-content table { width: 100%; border-collapse: collapse; margin-bottom: 1em; }
        .doc-content table th,.doc-content table td { padding: 8px 12px; border: 1px solid var(--border-color); }
        .doc-content table th { font-weight: 600; }
        .doc-content code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 13px; font-family: monospace; }
        .doc-content pre { background: #f1f5f9; padding: 12px 16px; border-radius: 8px; overflow-x: auto; margin-bottom: 1em; }
        .doc-content blockquote { border-left: 4px solid var(--primary); padding-left: 16px; color: var(--text-secondary); margin-bottom: .8em; }
        .doc-content img { max-width: 100%; border-radius: 8px; }
    </style>
</x-app-layout>
