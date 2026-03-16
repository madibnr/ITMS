<x-app-layout>
    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size:22px; font-weight:700; color:var(--text-primary);">Kelola Panduan & FAQ</h1>
            <p style="font-size:13px; color:var(--text-muted); margin-top:4px;">Konten yang tampil di halaman publik /helpdesk/guide</p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('guide.tips.create') }}" style="background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;">+ Solusi Mandiri</a>
            <a href="{{ route('guide.faqs.create') }}" style="background:linear-gradient(135deg,#10b981,#059669); color:white; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;">+ FAQ</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

        {{-- ── Solusi Mandiri ───────────────────── --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Solusi Mandiri <span style="font-size:12px; font-weight:400; color:var(--text-muted);">(tip cards)</span></div>
            </div>
            <div class="panel-body" style="padding:0;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:var(--bg-secondary);">
                            <th style="padding:10px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Judul</th>
                            <th style="padding:10px 16px; text-align:center; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Status</th>
                            <th style="padding:10px 16px; text-align:center; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Urutan</th>
                            <th style="padding:10px 16px; text-align:right; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tips as $tip)
                        <tr style="border-top:1px solid var(--border-color);">
                            <td style="padding:10px 16px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="width:28px; height:28px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; background:{{ $tip->icon_bg }}; color:{{ $tip->icon_color }};"><i class="{{ $tip->icon }}"></i></span>
                                    <span style="font-weight:600; color:var(--text-primary);">{{ $tip->title }}</span>
                                </div>
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                @if($tip->is_active)
                                    <span style="background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600;">Aktif</span>
                                @else
                                    <span style="background:#f3f4f6; color:#6b7280; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding:10px 16px; text-align:center; color:var(--text-muted);">{{ $tip->sort_order }}</td>
                            <td style="padding:10px 16px; text-align:right;">
                                <a href="{{ route('guide.tips.edit', $tip) }}" style="color:var(--primary); font-size:12px; font-weight:600; text-decoration:none; margin-right:10px;">Edit</a>
                                <form method="POST" action="{{ route('guide.tips.destroy', $tip) }}" style="display:inline;"
                                      onsubmit="return confirm('Hapus solusi mandiri ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#ef4444; font-size:12px; font-weight:600; cursor:pointer;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="padding:24px; text-align:center; color:var(--text-muted); font-size:13px;">Belum ada solusi mandiri.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── FAQ ─────────────────────────────── --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">FAQ <span style="font-size:12px; font-weight:400; color:var(--text-muted);">(accordion)</span></div>
            </div>
            <div class="panel-body" style="padding:0;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:var(--bg-secondary);">
                            <th style="padding:10px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Pertanyaan</th>
                            <th style="padding:10px 16px; text-align:center; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Status</th>
                            <th style="padding:10px 16px; text-align:center; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Urutan</th>
                            <th style="padding:10px 16px; text-align:right; color:var(--text-muted); font-weight:600; font-size:11px; text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr style="border-top:1px solid var(--border-color);">
                            <td style="padding:10px 16px; max-width:200px;">
                                <span style="color:var(--text-primary); display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $faq->question }}</span>
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                @if($faq->is_active)
                                    <span style="background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600;">Aktif</span>
                                @else
                                    <span style="background:#f3f4f6; color:#6b7280; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding:10px 16px; text-align:center; color:var(--text-muted);">{{ $faq->sort_order }}</td>
                            <td style="padding:10px 16px; text-align:right;">
                                <a href="{{ route('guide.faqs.edit', $faq) }}" style="color:var(--primary); font-size:12px; font-weight:600; text-decoration:none; margin-right:10px;">Edit</a>
                                <form method="POST" action="{{ route('guide.faqs.destroy', $faq) }}" style="display:inline;"
                                      onsubmit="return confirm('Hapus FAQ ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#ef4444; font-size:12px; font-weight:600; cursor:pointer;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="padding:24px; text-align:center; color:var(--text-muted); font-size:13px;">Belum ada FAQ.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
