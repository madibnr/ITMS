@extends('layouts.helpdesk')

@section('title', 'Lacak Tiket — IT Helpdesk')

@section('styles')
<style>
    .track-container {
        max-width: 520px;
        margin: 60px auto;
        text-align: center;
    }
    .track-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
        color: var(--white);
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .track-container h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--gray-800);
        margin-bottom: 8px;
    }
    .track-container > p {
        color: var(--gray-500);
        font-size: 14px;
        margin-bottom: 32px;
    }
    .track-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 32px;
        text-align: left;
    }
</style>
@endsection

@section('content')
<div class="track-container animate-in">
    <div class="track-icon"><i class="fas fa-search"></i></div>
    <h1>Lacak Tiket Anda</h1>
    <p>Masukkan nomor ticket Anda untuk melihat status laporan</p>

    <div class="track-card">
        <form action="{{ route('helpdesk.track.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nomor Ticket <span class="required">*</span></label>
                <input type="text" name="ticket_number" class="form-control @error('ticket_number') is-invalid @enderror"
                       value="{{ old('ticket_number') }}" placeholder="Contoh: TKT-20260301-0001">
                @error('ticket_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;">
                <i class="fas fa-search"></i> Lacak Ticket
            </button>
        </form>
    </div>
</div>
@endsection
