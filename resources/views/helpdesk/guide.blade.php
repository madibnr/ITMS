@extends('layouts.helpdesk')

@section('title', 'Panduan & FAQ — IT Helpdesk')

@section('styles')
<style>
    .guide-header {
        text-align: center;
        margin-bottom: 36px;
    }
    .guide-header h1 {
        font-size: 26px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 8px;
    }
    .guide-header p {
        color: var(--gray-500);
        font-size: 15px;
    }

    .faq-section { margin-bottom: 32px; }
    .faq-section h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .faq-item {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 12px;
        overflow: hidden;
    }
    .faq-question {
        width: 100%;
        padding: 16px 20px;
        background: none;
        border: none;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-800);
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    .faq-question:hover { background: var(--gray-50); }
    .faq-question i {
        transition: transform 0.3s;
        color: var(--gray-400);
        flex-shrink: 0;
    }
    .faq-question.open i { transform: rotate(180deg); }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .faq-answer-inner {
        padding: 0 20px 16px;
        font-size: 14px;
        color: var(--gray-600);
        line-height: 1.7;
    }
    .faq-answer.open { max-height: 600px; }

    /* Tips cards */
    .guide-container {
        max-width: 820px;
        margin: 0 auto;
    }
    .tips-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-bottom: 32px;
    }
    .tip-card {
        flex: 0 1 240px;
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        text-align: center;
    }
    .tip-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 20px;
    }
    .tip-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 6px;
    }
    .tip-card p {
        font-size: 13px;
        color: var(--gray-500);
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .tips-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="guide-container">
<div class="guide-header animate-in">
    <h1><i class="fas fa-book-open"></i> Panduan & FAQ</h1>
    <p>Coba cek solusi mandiri sebelum membuat laporan baru</p>
</div>

{{-- ── Solusi Mandiri (Tip Cards) ─────────────────────────── --}}
@if($tips->isNotEmpty())
<div class="tips-grid animate-in" style="animation-delay:0.1s">
    @foreach($tips as $tip)
    <div class="tip-card">
        <div class="icon" style="background:{{ $tip->icon_bg }}; color:{{ $tip->icon_color }};"><i class="{{ $tip->icon }}"></i></div>
        <h4>{{ $tip->title }}</h4>
        <p>{{ $tip->body }}</p>
    </div>
    @endforeach
</div>
@endif

{{-- ── FAQ Accordion ────────────────────────────────────────── --}}
@if($faqs->isNotEmpty())
<div class="faq-section animate-in" style="animation-delay:0.15s">
    <h2><i class="fas fa-question-circle" style="color:var(--primary-lighter)"></i> Pertanyaan Umum</h2>

    @foreach($faqs as $faq)
    <div class="faq-item">
        <button class="faq-question" onclick="toggleFaq(this)">
            {{ $faq->question }}
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="faq-answer">
            <div class="faq-answer-inner">{!! $faq->answer !!}</div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($tips->isEmpty() && $faqs->isEmpty())
<div style="text-align:center; padding:48px; color:var(--gray-400);">
    <i class="fas fa-book-open" style="font-size:40px; margin-bottom:12px; display:block;"></i>
    <p>Belum ada konten panduan. Admin dapat menambahkannya melalui dashboard.</p>
</div>
@endif

<div style="text-align:center;">
    <a href="{{ route('helpdesk.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus"></i> Buat Tiket Sekarang
    </a>
</div>
</div>{{-- /.guide-container --}}
@endsection

@section('scripts')
<script>
    function toggleFaq(btn) {
        btn.classList.toggle('open');
        btn.nextElementSibling.classList.toggle('open');
    }
</script>
@endsection
