@extends('layouts.helpdesk')

@section('title', 'IT Helpdesk — Sistem Laporan Kendala')

@section('styles')
<style>
    .hero {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 32px;
        margin-bottom: 32px;
    }
    .hero-text h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 8px;
    }
    .hero-text p {
        color: var(--gray-500);
        font-size: 15px;
    }
    .hero-stats {
        display: flex;
        gap: 16px;
        flex-shrink: 0;
    }
    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 16px 24px;
        text-align: center;
        box-shadow: var(--shadow);
        min-width: 110px;
    }
    .stat-card .number {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-lighter);
    }
    .stat-card .label {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Search */
    .search-bar {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: var(--radius);
        padding: 24px 28px;
        margin-bottom: 32px;
    }
    .search-bar h3 {
        color: var(--white);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .search-input-wrap {
        position: relative;
    }
    .search-input-wrap input {
        width: 100%;
        padding: 14px 48px 14px 18px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .search-input-wrap .search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 16px;
    }

    /* Action cards */
    .action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .action-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .action-card.featured {
        background: linear-gradient(135deg, var(--primary-lighter), var(--primary-light));
        color: var(--white);
        grid-row: span 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .action-card .icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 16px;
    }
    .action-card.featured .icon {
        background: rgba(255,255,255,0.2);
        color: var(--white);
    }
    .action-card .icon-track { background: #fef3c7; color: #d97706; }
    .action-card .icon-guide { background: #d1fae5; color: #059669; }
    .action-card h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .action-card p {
        font-size: 14px;
        opacity: 0.85;
        line-height: 1.5;
    }
    .action-card .arrow {
        margin-top: 16px;
        font-size: 13px;
        font-weight: 600;
        opacity: 0.8;
    }
    .action-card.featured .arrow { color: rgba(255,255,255,0.9); }

    /* How it works */
    .steps-section {
        margin-top: 40px;
    }
    .steps-section h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-800);
        margin-bottom: 24px;
        text-align: center;
    }
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .step-card {
        text-align: center;
        padding: 24px 16px;
    }
    .step-number {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-lighter));
        color: var(--white);
        font-size: 20px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
    }
    .step-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 6px;
    }
    .step-card p {
        font-size: 13px;
        color: var(--gray-500);
    }

    @media (max-width: 768px) {
        .hero { flex-direction: column; }
        .hero-stats { width: 100%; }
        .action-grid { grid-template-columns: 1fr; }
        .action-card.featured { grid-row: span 1; }
        .steps-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .steps-grid { grid-template-columns: 1fr; }
        .hero-stats { flex-direction: column; }
    }
</style>
@endsection

@section('content')
{{-- Hero --}}
<div class="hero animate-in">
    <div class="hero-text">
        <h1>Sistem Laporan Kendala</h1>
        <p>Pusat bantuan teknis IT terintegrasi untuk seluruh divisi</p>
    </div>
    <div class="hero-stats">
        <div class="stat-card">
            <div class="number">{{ $activeCount }}</div>
            <div class="label">Tiket Aktif</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $resolvedTodayCount }}</div>
            <div class="label">Selesai Hari Ini</div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="search-bar animate-in" style="animation-delay:0.1s">
    <h3>Ada kendala apa hari ini?</h3>
    <div class="search-input-wrap">
        <input type="text" placeholder="Cari solusi mandiri: internet mati, printer error, aplikasi error dll..." readonly onclick="window.location='{{ route('helpdesk.guide') }}'">
        <i class="fas fa-search search-icon"></i>
    </div>
</div>

{{-- Action Cards --}}
<div class="action-grid animate-in" style="animation-delay:0.2s">
    <a href="{{ route('helpdesk.create') }}" class="action-card featured">
        <div class="icon"><i class="fas fa-plus"></i></div>
        <h3>Buat Tiket Baru</h3>
        <p>Laporkan kerusakan hardware, software, atau kendala operasional lainnya.</p>
        <div class="arrow">Klik untuk buat laporan →</div>
    </a>
    <a href="{{ route('helpdesk.track') }}" class="action-card">
        <div class="icon icon-track"><i class="fas fa-search"></i></div>
        <h3>Lacak Tiket</h3>
        <p>Cek progres perbaikan laporanmu</p>
    </a>
    <a href="{{ route('helpdesk.guide') }}" class="action-card">
        <div class="icon icon-guide"><i class="fas fa-book-open"></i></div>
        <h3>FAQ & Panduan</h3>
        <p>Solusi mandiri sebelum lapor</p>
    </a>
</div>

{{-- How It Works --}}
<div class="steps-section animate-in" style="animation-delay:0.3s">
    <h2>Bagaimana Cara Kerjanya?</h2>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <h4>Buat Laporan</h4>
            <p>Isi formulir dengan detail kendala yang Anda alami</p>
        </div>
        <div class="step-card">
            <div class="step-number">2</div>
            <h4>Terima Konfirmasi</h4>
            <p>Nomor tiket dan link tracking dikirim ke email Anda</p>
        </div>
        <div class="step-card">
            <div class="step-number">3</div>
            <h4>Tim IT Proses</h4>
            <p>Tim IT kami akan segera menindaklanjuti laporan</p>
        </div>
        <div class="step-card">
            <div class="step-number">4</div>
            <h4>Selesai!</h4>
            <p>Kendala teratasi, Anda mendapat notifikasi</p>
        </div>
    </div>
</div>
@endsection
