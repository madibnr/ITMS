@extends('layouts.helpdesk')

@section('title', 'Status Tiket — IT Helpdesk')

@section('styles')
<style>
    .result-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .result-header h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-800);
    }
    .ticket-number-big {
        font-size: 14px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 8px;
        background: var(--primary);
        color: var(--white);
        letter-spacing: 0.5px;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .detail-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
    }
    .detail-card h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gray-100);
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--gray-50);
        font-size: 14px;
    }
    .detail-row:last-child { border: none; }
    .detail-label { color: var(--gray-500); }
    .detail-value { font-weight: 600; color: var(--gray-800); text-align: right; }
    .description-box {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 16px;
        font-size: 14px;
        color: var(--gray-700);
        line-height: 1.7;
        margin-top: 12px;
        white-space: pre-wrap;
    }

    /* Timeline */
    .timeline-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
    }
    .timeline-card h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gray-100);
    }
    .timeline-item {
        display: flex;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--gray-50);
    }
    .timeline-item:last-child { border: none; }
    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary-lighter);
        margin-top: 5px;
        flex-shrink: 0;
    }
    .timeline-content p { font-size: 14px; color: var(--gray-700); }
    .timeline-date { font-size: 12px; color: var(--gray-400); margin-top: 2px; }

    /* Status progress */
    .status-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 28px;
        position: relative;
    }
    .status-progress::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 15%;
        right: 15%;
        height: 3px;
        background: var(--gray-200);
    }
    .status-step {
        text-align: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }
    .status-step .dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gray-200);
        color: var(--gray-400);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 16px;
        transition: all 0.3s;
    }
    .status-step.active .dot {
        background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
        color: var(--white);
        box-shadow: 0 3px 12px rgba(30,58,95,0.3);
    }
    .status-step.done .dot {
        background: var(--green);
        color: var(--white);
    }
    .status-step .label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-400);
    }
    .status-step.active .label,
    .status-step.done .label { color: var(--gray-700); }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="result-header animate-in">
    <h1><i class="fas fa-ticket-alt" style="color:var(--primary-lighter)"></i> Status Tiket Anda</h1>
    <div class="ticket-number-big">{{ $ticket->ticket_number }}</div>
</div>

{{-- Status Progress Bar --}}
@php
    $statuses = ['Open', 'In Progress', 'Resolved', 'Closed'];
    $currentIdx = array_search($ticket->status, $statuses);
@endphp
<div class="status-progress animate-in" style="animation-delay:0.1s">
    @foreach($statuses as $idx => $st)
        <div class="status-step {{ $idx < $currentIdx ? 'done' : ($idx == $currentIdx ? 'active' : '') }}">
            <div class="dot">
                @if($idx < $currentIdx)
                    <i class="fas fa-check"></i>
                @elseif($idx == $currentIdx)
                    <i class="fas fa-circle" style="font-size:10px"></i>
                @else
                    <i class="fas fa-circle" style="font-size:8px"></i>
                @endif
            </div>
            <div class="label">{{ $st }}</div>
        </div>
    @endforeach
</div>

{{-- Detail --}}
<div class="detail-grid animate-in" style="animation-delay:0.15s">
    <div class="detail-card">
        <h3><i class="fas fa-info-circle" style="color:var(--primary-lighter)"></i> Informasi Tiket</h3>
        <div class="detail-row">
            <span class="detail-label">Judul</span>
            <span class="detail-value">{{ $ticket->title }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Kategori</span>
            <span class="detail-value">{{ $ticket->category->name ?? '-' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Prioritas</span>
            <span class="detail-value">
                <span class="badge badge-{{ strtolower($ticket->priority) }}">{{ $ticket->priority }}</span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">
                @php
                    $badgeClass = match($ticket->status) {
                        'Open' => 'badge-open',
                        'In Progress' => 'badge-progress',
                        'Resolved' => 'badge-resolved',
                        'Closed' => 'badge-closed',
                        default => 'badge-open',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $ticket->status }}</span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">SLA Deadline</span>
            <span class="detail-value" style="{{ $ticket->isOverdue() ? 'color:var(--red)' : '' }}">
                {{ $ticket->sla_deadline?->format('d M Y, H:i') ?? '-' }}
                @if($ticket->isOverdue()) <i class="fas fa-exclamation-triangle"></i> @endif
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ditangani Oleh</span>
            <span class="detail-value">{{ $ticket->assignee->name ?? 'Belum ditugaskan' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Lapor</span>
            <span class="detail-value">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
        </div>
    </div>

    <div class="detail-card">
        <h3><i class="fas fa-align-left" style="color:var(--primary-lighter)"></i> Deskripsi Kendala</h3>
        <div class="description-box">{{ $ticket->description }}</div>

        @if($ticket->resolution_note)
            <h3 style="margin-top:20px"><i class="fas fa-check-circle" style="color:var(--green)"></i> Catatan Penyelesaian</h3>
            <div class="description-box" style="background:#d1fae5; border: 1px solid #a7f3d0;">{{ $ticket->resolution_note }}</div>
        @endif
    </div>
</div>

{{-- Comments (public only) --}}
@if($ticket->comments->isNotEmpty())
<div class="timeline-card animate-in" style="animation-delay:0.2s">
    <h3><i class="fas fa-comments" style="color:var(--primary-lighter)"></i> Update Terbaru</h3>
    @foreach($ticket->comments as $comment)
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <p>{{ $comment->content }}</p>
                <div class="timeline-date">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @endforeach
</div>
@endif

<div style="text-align:center; margin-top:28px;" class="animate-in" style="animation-delay:0.25s">
    <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary"><i class="fas fa-home"></i> Kembali ke Beranda</a>
</div>
@endsection
