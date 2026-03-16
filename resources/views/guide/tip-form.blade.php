<x-app-layout>
    <div style="max-width:640px; margin:0 auto;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('guide.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:13px;">← Kembali</a>
            <h1 style="font-size:20px; font-weight:700; color:var(--text-primary);">
                {{ isset($tip) ? 'Edit Solusi Mandiri' : 'Tambah Solusi Mandiri' }}
            </h1>
        </div>

        <div class="panel">
            <div class="panel-body">
                <form method="POST" action="{{ isset($tip) ? route('guide.tips.update', $tip) : route('guide.tips.store') }}">
                    @csrf
                    @if(isset($tip)) @method('PUT') @endif

                    {{-- Icon Class --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Icon (FontAwesome class) *</label>
                        <input type="text" name="icon" value="{{ old('icon', $tip->icon ?? 'fas fa-lightbulb') }}" required
                               placeholder="Contoh: fas fa-wifi"
                               style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none; font-family:inherit;">
                        <p style="font-size:11px; color:var(--text-muted); margin-top:4px;">Cari icon di <a href="https://fontawesome.com/icons" target="_blank" style="color:var(--primary);">fontawesome.com/icons</a></p>
                        @error('icon') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Icon Colors --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Warna Latar Icon *</label>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <input type="color" name="icon_bg" value="{{ old('icon_bg', $tip->icon_bg ?? '#dbeafe') }}"
                                       style="width:40px; height:36px; border:1px solid var(--border-color); border-radius:6px; cursor:pointer; padding:2px;">
                                <input type="text" id="icon_bg_text" value="{{ old('icon_bg', $tip->icon_bg ?? '#dbeafe') }}"
                                       style="flex:1; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:13px; outline:none;"
                                       oninput="syncColor('icon_bg_text','icon_bg_color')">
            </div>
                            <input type="hidden" name="icon_bg" id="icon_bg_color" value="{{ old('icon_bg', $tip->icon_bg ?? '#dbeafe') }}">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Warna Icon *</label>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <input type="color" name="icon_color_picker" value="{{ old('icon_color', $tip->icon_color ?? '#2563eb') }}"
                                       style="width:40px; height:36px; border:1px solid var(--border-color); border-radius:6px; cursor:pointer; padding:2px;"
                                       oninput="syncColorPicker(this,'icon_color_text','icon_color')">
                                <input type="text" id="icon_color_text" value="{{ old('icon_color', $tip->icon_color ?? '#2563eb') }}"
                                       style="flex:1; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:13px; outline:none;"
                                       oninput="syncColor('icon_color_text','icon_color')">
                            </div>
                            <input type="hidden" name="icon_color" id="icon_color" value="{{ old('icon_color', $tip->icon_color ?? '#2563eb') }}">
                        </div>
                    </div>

                    {{-- Title --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Judul *</label>
                        <input type="text" name="title" value="{{ old('title', $tip->title ?? '') }}" required maxlength="255"
                               placeholder="Contoh: Internet Bermasalah?"
                               style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none; font-family:inherit;">
                        @error('title') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Body --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Isi / Solusi *</label>
                        <textarea name="body" rows="3" required
                                  placeholder="Langkah-langkah solusi mandiri..."
                                  style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none; font-family:inherit; resize:vertical;">{{ old('body', $tip->body ?? '') }}</textarea>
                        @error('body') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sort Order + Active --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Urutan Tampil</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $tip->sort_order ?? 0) }}" min="0"
                                   style="width:100%; padding:8px 12px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">Status</label>
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; padding:8px 0;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tip->is_active ?? true) ? 'checked' : '' }}
                                       style="width:16px; height:16px;">
                                <span style="font-size:14px;">Aktif (tampil di publik)</span>
                            </label>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button type="submit" style="background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white; padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
                            {{ isset($tip) ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <a href="{{ route('guide.index') }}" style="background:#f1f5f9; color:var(--text-secondary); padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function syncColor(textId, hiddenId) {
        document.getElementById(hiddenId).value = document.getElementById(textId).value;
    }
    function syncColorPicker(picker, textId, hiddenId) {
        document.getElementById(textId).value = picker.value;
        document.getElementById(hiddenId).value = picker.value;
    }
    // Sync color pickers on load
    document.querySelector('[name="icon_bg"]')?.addEventListener('input', function() {
        document.getElementById('icon_bg_text').value = this.value;
        document.getElementById('icon_bg_color').value = this.value;
    });
    </script>
</x-app-layout>
