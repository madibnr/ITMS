<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Ticket History</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Resolved & closed tickets</p>
        </div>
        <a href="{{ route('tickets.history.export') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; margin-right: 4px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export Excel
        </a>
    </div>

    {{-- Filters --}}
    <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('tickets.history') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tickets..." style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; width: 200px; outline: none;">
                <select name="status" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Status</option>
                    @foreach(['Resolved', 'Closed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <select name="priority" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Priority</option>
                    @foreach(['Low', 'Medium', 'High', 'Critical'] as $p)
                        <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                <select name="category_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" style="background: var(--text-primary); color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Filter</button>
                <a href="{{ route('tickets.history') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">Clear</a>
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Resolved At</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="link-primary" style="font-weight: 600; font-family: monospace; font-size: 13px;">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td style="color: var(--text-primary); font-weight: 500;">{{ Str::limit($ticket->title, 45) }}</td>
                            <td><span class="badge badge-{{ strtolower($ticket->priority) }}">{{ $ticket->priority }}</span></td>
                            <td><span class="badge badge-{{ strtolower(str_replace(' ', '-', $ticket->status)) }}">{{ $ticket->status }}</span></td>
                            <td style="color: var(--text-secondary);">
                                @php
                                    $creatorName = $ticket->creator->name ?? $ticket->reporter->full_name ?? '-';
                                @endphp
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg, {{ $ticket->source === 'public' ? '#fef3c7, #fde68a' : '#e0e7ff, #c7d2fe' }}); display: flex; align-items: center; justify-content: center; color: {{ $ticket->source === 'public' ? '#92400e' : 'var(--primary)' }}; font-size: 10px; font-weight: 600;">
                                        {{ strtoupper(substr($creatorName, 0, 2)) }}
                                    </div>
                                    {{ $creatorName }}
                                    @if($ticket->source === 'public')
                                        <span style="font-size: 10px; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 8px; font-weight: 600;">Public</span>
                                    @endif
                                </div>
                            </td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $ticket->resolved_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $ticket->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">
                                No ticket history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px;">{{ $tickets->withQueryString()->links() }}</div>
        </div>
    </div>
</x-app-layout>
