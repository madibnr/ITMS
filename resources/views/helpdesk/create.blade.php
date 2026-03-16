@extends('layouts.helpdesk')

@section('title', 'Buat Tiket — IT Helpdesk')

@section('styles')
<style>
    .page-header {
        margin-bottom: 28px;
    }
    .page-header h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 4px;
    }
    .page-header p {
        color: var(--gray-500);
        font-size: 14px;
    }
    .form-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .form-section-title {
        padding: 16px 24px;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-body { padding: 24px; }
    .form-section-body + .form-section-title { border-top: 1px solid var(--gray-200); }

    .file-upload {
        border: 2px dashed var(--gray-300);
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }
    .file-upload:hover { border-color: var(--primary-lighter); background: #f7fafc; }
    .file-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }
    .file-upload .icon { font-size: 28px; color: var(--gray-400); margin-bottom: 8px; }
    .file-upload .text { font-size: 14px; color: var(--gray-500); }
    .file-upload .hint { font-size: 12px; color: var(--gray-400); margin-top: 4px; }
    .file-name {
        margin-top: 8px;
        font-size: 13px;
        color: var(--primary-lighter);
        font-weight: 600;
    }

    .form-actions {
        padding: 20px 24px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
</style>
@endsection

@section('content')
<div class="page-header animate-in">
    <h1><i class="fas fa-plus-circle"></i> Buat Tiket Baru</h1>
    <p>Isi formulir di bawah ini untuk melaporkan kendala IT Anda</p>
</div>

<form action="{{ route('helpdesk.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-card animate-in" style="animation-delay:0.1s">
        {{-- Reporter Data --}}
        <div class="form-section-title"><i class="fas fa-user"></i> Data Pelapor</div>
        <div class="form-section-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                           value="{{ old('full_name') }}" placeholder="Masukkan nama lengkap">
                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">NIK (Nomor Induk Karyawan) <span class="required">*</span></label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                           value="{{ old('nik') }}" placeholder="Masukkan NIK">
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp <span class="required">*</span></label>
                    <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                           value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx">
                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@perusahaan.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Ticket Data --}}
        <div class="form-section-title"><i class="fas fa-ticket-alt"></i> Detail Kendala</div>
        <div class="form-section-body">
            <div class="form-group">
                <label class="form-label">Judul Laporan <span class="required">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" placeholder="Ringkasan singkat kendala, contoh: Printer lantai 3 tidak bisa print">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Kendala <span class="required">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="5" placeholder="Jelaskan detail kendala yang Anda alami. Semakin detail, semakin cepat kami bisa membantu...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Prioritas <span class="required">*</span></label>
                    <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                        @foreach($priorities as $p)
                            <option value="{{ $p }}" {{ old('priority', 'Medium') == $p ? 'selected' : '' }}>
                                {{ $p }}
                                @if($p === 'Low') — Tidak mendesak
                                @elseif($p === 'Medium') — Normal
                                @elseif($p === 'High') — Mendesak
                                @elseif($p === 'Critical') — Sangat mendesak
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Lampiran (opsional)</label>
                <div class="file-upload" id="fileUploadArea">
                    <input type="file" name="attachment" id="attachmentInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                           onchange="showFileName(this)">
                    <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="text">Klik, seret file ke sini, atau <strong>tempel screenshot (Ctrl+V)</strong></div>
                    <div class="hint">Format: JPG, PNG, PDF, DOC, DOCX — Maks 5MB</div>
                    <div class="file-name" id="fileName"></div>
                </div>
                @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Kirim Laporan
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function showFileName(input) {
        const name = input.files[0]?.name ?? '';
        document.getElementById('fileName').textContent = name ? '📎 ' + name : '';
    }

    // ── Paste screenshot support (Ctrl+V) ──────────────────────────────
    document.addEventListener('paste', function (e) {
        const items = (e.clipboardData || e.originalEvent?.clipboardData)?.items;
        if (!items) return;

        for (const item of items) {
            if (!item.type.startsWith('image/')) continue;

            const blob = item.getAsFile();
            if (!blob) continue;

            // Build a proper filename with timestamp
            const ext  = item.type.split('/')[1] || 'png';
            const name = 'screenshot-' + Date.now() + '.' + ext;
            const file = new File([blob], name, { type: item.type });

            // Inject file into the input element
            const input = document.getElementById('attachmentInput');
            const dt    = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;

            // Show feedback
            document.getElementById('fileName').textContent = '📎 ' + name;

            // Flash the upload area to indicate success
            const area = document.getElementById('fileUploadArea');
            area.style.borderColor  = 'var(--primary-lighter)';
            area.style.background   = '#f0f7ff';
            setTimeout(() => {
                area.style.borderColor = '';
                area.style.background  = '';
            }, 1500);

            e.preventDefault();
            break; // only take the first image
        }
    });
</script>
@endsection
