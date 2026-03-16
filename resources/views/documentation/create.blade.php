<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Create Documentation</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                <a href="{{ route('docs.index') }}" style="color: var(--text-muted); text-decoration: none;">IT Documentation</a> / New Document
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('docs.store') }}" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 20px;">
            {{-- Main Column --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                {{-- Basic Info --}}
                <div class="panel">
                    <div class="panel-body" style="padding: 24px;">
                        <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Document Information</h3>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Category *</label>
                            <select name="category_id" id="category_id" required onchange="loadMetaFields(this)" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                                <option value="">Select Category…</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Document title…" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            @error('title') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Short Description</label>
                            <input type="text" name="description" value="{{ old('description') }}" placeholder="Brief summary (optional)…" maxlength="1000" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            @error('description') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="panel">
                    <div class="panel-body" style="padding: 24px;">
                        <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">Content *</h3>
                        <textarea name="content" id="content-editor" rows="16" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('content') }}</textarea>
                        @error('content') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Dynamic Meta Fields --}}
                <div class="panel" id="meta-fields-card" style="display: none;">
                    <div class="panel-body" style="padding: 24px;">
                        <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">Structured Fields</h3>
                        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">Additional fields specific to this documentation type.</p>
                        <div id="meta-fields-container"></div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="panel">
                    <div class="panel-body" style="padding: 24px;">
                        <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Publish Options</h3>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Status *</label>
                            <select name="status" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                                <option value="draft" {{ old('status','draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Tags</label>
                            <select name="tags[]" multiple style="width: 100%; padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; height: 110px;">
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Hold Ctrl/Cmd to select multiple.</div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Attachment</label>
                            <input type="file" name="attachment" accept=".pdf,.docx,.xlsx,.png,.jpg,.jpeg" style="width: 100%; padding: 8px 0; font-size: 13px;">
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">PDF, DOCX, XLSX, PNG, JPG (max 10 MB)</div>
                            @error('attachment') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;">
                            <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; width: 100%;">Save Document</button>
                            <a href="{{ route('docs.index') }}" style="display: block; text-align: center; padding: 10px 20px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- TinyMCE (free, no API key required) --}}
    <script src="https://unpkg.com/tinymce@6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content-editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align lineheight | numlist bullist indent outdent | codesample | removeformat',
            height: 400,
            promotion: false,
            branding: false,
        });

        const metaDefinitions = {
            'network-documentation': [
                { key: 'device_name',  label: 'Device Name' },
                { key: 'ip_address',   label: 'IP Address' },
                { key: 'vlan',         label: 'VLAN' },
                { key: 'location',     label: 'Location' },
                { key: 'notes',        label: 'Notes', textarea: true },
            ],
            'server-rack-documentation': [
                { key: 'rack_name',        label: 'Rack Name' },
                { key: 'rack_location',    label: 'Rack Location' },
                { key: 'u_position',       label: 'U Position' },
                { key: 'device_installed', label: 'Device Installed', textarea: true },
            ],
            'account-documentation': [
                { key: 'system_name',  label: 'System Name' },
                { key: 'account_type', label: 'Account Type' },
                { key: 'username',     label: 'Username' },
                { key: 'description',  label: 'Description' },
                { key: 'notes',        label: 'Notes', textarea: true },
            ],
            'sop-standard-operating-procedure': [
                { key: 'responsible_team', label: 'Responsible Team' },
                { key: 'procedure_steps',  label: 'Procedure Steps', textarea: true },
            ],
        };

        const oldMeta = @json(old('meta', []));
        const inputStyle = 'width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; box-sizing: border-box;';
        const labelStyle = 'display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;';

        function loadMetaFields(select) {
            const slug = select.options[select.selectedIndex]?.dataset.slug || '';
            const card = document.getElementById('meta-fields-card');
            const container = document.getElementById('meta-fields-container');
            const fields = metaDefinitions[slug] || [];
            if (fields.length === 0) { card.style.display = 'none'; return; }
            card.style.display = 'block';
            container.innerHTML = fields.map(f => {
                const val = oldMeta[f.key] || '';
                const input = f.textarea
                    ? `<textarea name="meta[${f.key}]" rows="3" style="${inputStyle} resize: vertical;">${val}</textarea>`
                    : `<input type="text" name="meta[${f.key}]" value="${val}" style="${inputStyle}">`;
                return `<div style="margin-bottom: 16px;"><label style="${labelStyle}">${f.label}</label>${input}</div>`;
            }).join('');
        }

        const catSel = document.getElementById('category_id');
        if (catSel.value) loadMetaFields(catSel);
    </script>
</x-app-layout>
