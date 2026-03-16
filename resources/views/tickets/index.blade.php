<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Tickets</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Active support requests (Open & In Progress)</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('tickets.export') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('tickets.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Ticket
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('tickets.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tickets..." style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; width: 200px; outline: none;">
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
                <a href="{{ route('tickets.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">Clear</a>
            </form>
        </div>
    </div>

    {{-- Tickets Table --}}
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
                            <th>Assigned To</th>
                            <th>SLA</th>
                            <th>Created</th>
                            <th>Action</th>
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
                            <td style="color: var(--text-secondary);">{{ $ticket->assignee->name ?? '-' }}</td>
                            <td style="font-size: 13px; {{ $ticket->isOverdue() ? 'color: var(--danger); font-weight: 700;' : 'color: var(--text-muted);' }}">
                                {{ $ticket->sla_deadline?->format('d M H:i') ?? '-' }}
                            </td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td style="white-space: nowrap;">
                                @if($ticket->status !== 'Closed')
                                <form method="POST" action="{{ route('tickets.complete', $ticket) }}" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="badge badge-active" style="border: none; cursor: pointer; padding: 5px 12px; font-size: 11px;" onclick="return confirm('Mark this ticket as completed?')">
                                        ✓ Complete
                                    </button>
                                </form>
                                @endif
                                @if(auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" style="display: inline;"
                                      onsubmit="return confirm('Hapus tiket {{ $ticket->ticket_number }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: 1px solid #fca5a5; color: #ef4444; border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 600; cursor: pointer; line-height: 1.4;"
                                            onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='none'">
                                        🗑 Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">
                                No active tickets found.
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
