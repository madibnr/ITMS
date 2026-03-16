<x-app-layout>
    <div style="max-width:640px; margin:0 auto;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('guide.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:13px;">← Kembali</a>
            <h1 style="font-size:20px; font-weight:700; color:var(--text-primary);">
                {{ isset($faq) ? 'Edit FAQ' : 'Tambah FAQ' }}
            </h1>
        </div>

        <div class="panel">
            <div class="panel-body">
                <form method="POST" action="{{ isset($faq) ? route('guide.faqs.update', $faq) : route('guide.faqs.store') }}">
                    @csrf
                    @if(isset($faq)) @method('PUT') @endif

                    {{-- Question --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Pertanyaan *</label>
                        <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}" required maxlength="255"
                               placeholder="Contoh: Bagaimana cara membuat tiket baru?"
                               style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none; font-family:inherit;">
                        @error('question') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Answer --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Jawaban * <span style="font-weight:400;">(HTML diperbolehkan, contoh: &lt;strong&gt;text&lt;/strong&gt;)</span></label>
                        <textarea name="answer" rows="5" required
                                  placeholder="Tulis jawaban lengkap di sini..."
                                  style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none; font-family:inherit; resize:vertical;">{{ old('answer', $faq->answer ?? '') }}</textarea>
                        @error('answer') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sort Order + Active --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Urutan Tampil</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0"
                                   style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Status</label>
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; padding:8px 0;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}
                                       style="width:16px; height:16px;">
                                <span style="font-size:14px;">Aktif (tampil di publik)</span>
                            </label>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button type="submit" style="background:linear-gradient(135deg,#10b981,#059669); color:white; padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
                            {{ isset($faq) ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <a href="{{ route('guide.index') }}" style="background:#f1f5f9; color:var(--text-secondary); padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
