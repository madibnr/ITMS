@extends('layouts.helpdesk')

@section('title', 'Tiket Berhasil Dibuat — IT Helpdesk')

@section('styles')
<style>
    .success-container {
        max-width: 560px;
        margin: 48px auto;
        text-align: center;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #059669, #34d399);
        color: var(--white);
        font-size: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        box-shadow: 0 8px 24px rgba(5,150,105,0.3);
        animation: scaleIn 0.5s ease-out;
    }
    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
    .success-container h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--gray-800);
        margin-bottom: 8px;
    }
    .success-container > p {
        color: var(--gray-500);
        font-size: 14px;
        margin-bottom: 28px;
    }
    .ticket-number-display {
        background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
        color: var(--white);
        padding: 16px 28px;
        border-radius: 10px;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 28px;
        box-shadow: 0 4px 16px rgba(30,58,95,0.25);
    }
    .info-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        text-align: left;
        margin-bottom: 24px;
    }
    .info-card h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-700);
        margin-bottom: 12px;
    }
    .info-card ul {
        list-style: none;
        padding: 0;
    }
    .info-card li {
        padding: 8px 0;
        font-size: 14px;
        color: var(--gray-600);
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border-bottom: 1px solid var(--gray-50);
    }
    .info-card li:last-child { border: none; }
    .info-card li i {
        color: var(--primary-lighter);
        margin-top: 3px;
        flex-shrink: 0;
    }
    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
</style>
@endsection

@section('content')
<div class="success-container animate-in">
    <div class="success-icon"><i class="fas fa-check"></i></div>
    <h1>Laporan Berhasil Dikirim!</h1>
    <p>Terima kasih telah melaporkan kendala. Tim IT kami akan segera menindaklanjuti.</p>

    <div class="ticket-number-display">{{ $ticketNumber }}</div>

    <div class="info-card">
        <h3>🔔 Apa yang terjadi selanjutnya?</h3>
        <ul>
            <li>
                <i class="fas fa-envelope"></i>
                Email konfirmasi dan link tracking telah dikirim ke email Anda
            </li>
            <li>
                <i class="fas fa-user-cog"></i>
                Tim IT akan mereview laporan Anda dan menugaskan teknisi
            </li>
            <li>
                <i class="fas fa-search"></i>
                Anda bisa melacak status tiket kapan saja melalui halaman <strong>Lacak Tiket</strong>
            </li>
            <li>
                <i class="fas fa-bell"></i>
                Anda akan menerima notifikasi saat tiket diupdate melalui email
            </li>
        </ul>
    </div>

    <div class="action-buttons">
        @if($trackingUrl)
            <a href="{{ $trackingUrl }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Lacak Tiket Sekarang
            </a>
        @endif
        <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
