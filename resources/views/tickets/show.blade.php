<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">{{ $ticket->ticket_number }}</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">{{ $ticket->title }}</p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('tickets.edit', $ticket) }}" style="background: #f1f5f9; color: var(--text-secondary); padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">Edit</a>
            @if($ticket->status !== 'Closed')
                @if($ticket->status !== 'Resolved')
                <form method="POST" action="{{ route('tickets.resolve', $ticket) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="resolution_note" value="Resolved via quick action">
                    <button style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Resolve</button>
                </form>
                @endif
                <form method="POST" action="{{ route('tickets.complete', $ticket) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <button style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;" onclick="return confirm('Mark as completed?')">
                        ✓ Mark as Completed
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        {{-- Main Content --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            {{-- Description --}}
            <div class="panel">
                <div class="panel-body">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Description</h3>
                    <div style="color: var(--text-secondary); line-height: 1.7; font-size: 14px;">{!! nl2br(e($ticket->description)) !!}</div>
                    @if($ticket->resolution_note)
                        <div style="margin-top: 16px; padding: 14px; background: linear-gradient(135deg, #d1fae5, #ecfdf5); border-radius: 10px; border-left: 4px solid #10b981;">
                            <strong style="color: #065f46; font-size: 13px;">Resolution:</strong>
                            <p style="color: #047857; margin-top: 4px; font-size: 14px;">{{ $ticket->resolution_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Comments --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Comments ({{ $ticket->comments->count() }})</div>
                </div>
                <div class="panel-body">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($ticket->comments as $comment)
                        <div style="border-left: 3px solid {{ $comment->is_internal ? '#f59e0b' : 'var(--border-color)' }}; padding: 12px 16px; border-radius: 0 8px 8px 0; {{ $comment->is_internal ? 'background: #fffbeb;' : '' }}">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">
                                <span style="font-weight: 600; color: var(--text-primary);">{{ $comment->user->name }}</span>
                                <span>{{ $comment->created_at->diffForHumans() }} @if($comment->is_internal) <span style="color: #d97706;">(Internal)</span> @endif</span>
                            </div>
                            <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.5;">{{ $comment->body }}</p>
                        </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                        @csrf
                        <textarea name="body" rows="3" placeholder="Add a comment..." required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; resize: vertical; outline: none; font-family: inherit;"></textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-muted); cursor: pointer;">
                                <input type="checkbox" name="is_internal" value="1" style="border-radius: 4px;"> Internal note
                            </label>
                            <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Add Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="panel">
                <div class="panel-header"><div class="panel-title">Details</div></div>
                <div class="panel-body">
                    <div style="display: flex; flex-direction: column; gap: 14px; font-size: 14px;">
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Status</div>
                            <span class="badge badge-{{ strtolower(str_replace(' ', '-', $ticket->status)) }}">{{ $ticket->status }}</span>
                        </div>
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Priority</div>
                            <span class="badge badge-{{ strtolower($ticket->priority) }}">{{ $ticket->priority }}</span>
                        </div>
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Category</div>
                            <div style="color: var(--text-primary);">{{ $ticket->category->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Created By</div>
                            <div style="color: var(--text-primary);">
                                {{ $ticket->creator->name ?? $ticket->reporter->full_name ?? '-' }}
                                @if($ticket->source === 'public')
                                    <span style="font-size: 10px; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 8px; font-weight: 600; margin-left: 4px;">Public</span>
                                @endif
                            </div>
                        </div>

                        {{-- Reporter Info (Public Ticket) --}}
                        @if($ticket->reporter)
                        <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; margin-top: 0;">
                            <div style="color: var(--text-muted); font-size: 11px; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 10px;">Data Pelapor</div>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px;">
                                    <span style="color: var(--text-muted); min-width: 64px;">Nama</span>
                                    <span style="color: var(--text-primary); font-weight: 600;">{{ $ticket->reporter->full_name ?? '-' }}</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px;">
                                    <span style="color: var(--text-muted); min-width: 64px;">NIK</span>
                                    <span style="color: var(--text-primary); font-family: monospace;">{{ $ticket->reporter->nik ?? '-' }}</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px;">
                                    <span style="color: var(--text-muted); min-width: 64px;">Email</span>
                                    <span style="color: var(--text-primary); word-break: break-all;">{{ $ticket->reporter->email ?? '-' }}</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px;">
                                    <span style="color: var(--text-muted); min-width: 64px;">WhatsApp</span>
                                    @if($ticket->reporter->whatsapp)
                                        @php
                                            $wa = $ticket->reporter->whatsapp;
                                            // Normalize to international format: 08xxx → 628xxx
                                            $waInt = preg_replace('/^\+?0/', '62', preg_replace('/[\s\-()]/', '', $wa));
                                        @endphp
                                        <a href="https://wa.me/{{ $waInt }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           style="color: #25d366; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;"
                                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            {{ $wa }}
                                        </a>
                                    @else
                                        <span style="color: var(--text-primary);">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Assigned To</div>
                            <div style="color: var(--text-primary);">{{ $ticket->assignee->name ?? 'Unassigned' }}</div>
                        </div>
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 6px;">SLA Deadline</div>
                            {{-- Static display --}}
                            <div id="sla-display-{{ $ticket->id }}" style="display: flex; align-items: center; gap: 8px;">
                                <span style="{{ $ticket->isOverdue() ? 'color: var(--danger); font-weight: 700;' : 'color: var(--text-primary);' }}">
                                    {{ $ticket->sla_deadline?->format('d M Y H:i') ?? '-' }}
                                </span>
                                <button type="button"
                                    onclick="toggleSlaEdit({{ $ticket->id }}, true)"
                                    title="Edit SLA Deadline"
                                    style="background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 2px 4px; border-radius: 4px; font-size: 12px; line-height: 1;"
                                    onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">✏</button>
                            </div>
                            {{-- Inline edit form --}}
                            <div id="sla-form-{{ $ticket->id }}" style="display: none;">
                                <form method="POST" action="{{ route('tickets.update-sla', $ticket) }}" style="display: flex; flex-direction: column; gap: 6px;">
                                    @csrf @method('PATCH')
                                    <input
                                        type="datetime-local"
                                        name="sla_deadline"
                                        value="{{ $ticket->sla_deadline?->format('Y-m-d\TH:i') }}"
                                        required
                                        style="width: 100%; padding: 6px 10px; border: 1px solid var(--primary); border-radius: 6px; font-size: 13px; outline: none; font-family: inherit;"
                                    >
                                    <div style="display: flex; gap: 6px;">
                                        <button type="submit" style="flex: 1; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer;">Update</button>
                                        <button type="button" onclick="toggleSlaEdit({{ $ticket->id }}, false)" style="flex: 1; background: #f1f5f9; color: var(--text-secondary); padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer;">✕ Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Created</div>
                            <div style="color: var(--text-primary);">{{ $ticket->created_at->format('d M Y H:i') }}</div>
                        </div>
                        @if($ticket->resolved_at)
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Resolved</div>
                            <div style="color: #059669;">{{ $ticket->resolved_at->format('d M Y H:i') }}</div>
                        </div>
                        @endif
                        @if($ticket->closedByUser)
                        <div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Closed By</div>
                            <div style="color: var(--text-primary);">{{ $ticket->closedByUser->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Assign Form --}}
            @if($ticket->status !== 'Closed')
            <div class="panel">
                <div class="panel-header"><div class="panel-title">Assign Technician</div></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                        @csrf @method('PATCH')
                        <select name="assigned_to" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; margin-bottom: 10px; outline: none;">
                            <option value="">Select technician...</option>
                            @php
                                $technicians = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();
                            @endphp
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="width: 100%; background: var(--text-primary); color: white; padding: 8px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Assign</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
function toggleSlaEdit(ticketId, show) {
    document.getElementById('sla-display-' + ticketId).style.display = show ? 'none' : 'flex';
    document.getElementById('sla-form-' + ticketId).style.display   = show ? 'block' : 'none';
}
</script>
