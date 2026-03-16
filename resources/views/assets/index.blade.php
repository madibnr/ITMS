<x-app-layout>
    {{-- Dashboard Widgets --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Asset Inventory</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Enterprise IT Asset Management</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('assets.import') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                Import
            </a>
            <a href="{{ route('assets.export') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Export
            </a>
            {{-- Gear: Asset Tag Format Settings --}}
            <button onclick="openTagFormatModal()" title="Asset Tag Format Settings"
                style="background: none; border: 1px solid var(--border-color); border-radius: 8px; padding: 7px 10px; cursor: pointer; color: var(--text-secondary); display: inline-flex; align-items: center; transition: border-color .2s;"
                onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 17px; height: 17px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
            </button>
            <a href="{{ route('assets.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Asset
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: var(--primary);">{{ $summary['total'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Total Assets</div>
        </div>
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #10b981;">{{ $summary['active'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Active</div>
        </div>
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #f59e0b;">{{ $summary['in_repair'] ?? 0 }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">In Repair</div>
        </div>
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #3b82f6;">{{ $summary['in_stock'] ?? 0 }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">In Stock</div>
        </div>
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #ef4444;">{{ $summary['warranty_expiring'] }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Warranty Expiring</div>
        </div>
        <div class="panel" style="padding: 20px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #8b5cf6;">{{ $summary['assigned'] ?? 0 }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Assigned</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('assets.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..." style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; width: 200px; outline: none;">
                <select name="status" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Status</option>
                    @foreach(\App\Models\Asset::STATUSES as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <select name="category_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="location_id" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary);">
                    <input type="checkbox" name="warranty_expiring" value="1" {{ request('warranty_expiring') ? 'checked' : '' }}> Warranty Expiring
                </label>
                <button type="submit" style="background: var(--text-primary); color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Filter</button>
                <a href="{{ route('assets.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">Clear</a>
            </form>
        </div>
    </div>

    {{-- Assets Table --}}
    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Asset Tag</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Category</th>
                            <th>Assigned To</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                        <tr>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('assets.show', $asset) }}" class="link-primary" id="asset-tag-label-{{ $asset->id }}" style="font-weight: 600; font-family: monospace; font-size: 13px;">{{ $asset->asset_tag ?? $asset->asset_code }}</a>
                                <button type="button" title="Edit Asset Tag"
                                    onclick="openTagModal({{ $asset->id }}, '{{ addslashes($asset->asset_tag ?? $asset->asset_code) }}', '{{ route('assets.update-tag', $asset) }}')"
                                    style="background: none; border: 1px solid #c7d2fe; border-radius: 5px; padding: 2px 6px; margin-left: 4px; cursor: pointer; color: var(--primary); vertical-align: middle; line-height: 1;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 11px; height: 11px; vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                                </button>
                            </td>
                            <td style="color: var(--text-primary); font-weight: 500;">{{ $asset->name }}</td>
                            <td style="color: var(--text-secondary); font-size: 13px;">{{ $asset->display_model }}</td>
                            <td style="color: var(--text-secondary);">{{ $asset->category->name ?? '-' }}</td>
                            <td style="color: var(--text-secondary);">{{ $asset->assignedUser->name ?? '-' }}</td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $asset->display_location }}</td>
                            <td>
                                @php
                                    $badgeClass = match($asset->status) {
                                        'Active' => 'badge-active',
                                        'In Repair' => 'badge-in-progress',
                                        'In Stock' => 'badge-open',
                                        'Retired' => 'badge-closed',
                                        'Lost', 'Broken' => 'badge-closed',
                                        default => 'badge-open',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $asset->status }}</span>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('assets.show', $asset) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Detail</a>
                                <a href="{{ route('assets.edit', $asset) }}" style="color: #64748b; font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid var(--border-color); border-radius: 6px; margin-right: 4px;">Edit</a>
                                <a href="{{ route('assets.qrcode', $asset) }}" style="color: #8b5cf6; font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c4b5fd; border-radius: 6px;">QR</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">
                                No assets found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $assets->withQueryString()->links() }}
        </div>
    </div>
    {{-- ─── Change Asset Tag Modal ─── --}}
    <div id="tag-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.45); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; padding:32px 28px; width:420px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,.25); position:relative;">
            <button onclick="closeTagModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; cursor:pointer; color:#94a3b8;">&times;</button>
            <h3 style="font-size:16px; font-weight:700; color:var(--text-primary); margin-bottom:4px;">Edit Asset Tag</h3>
            <p id="tag-modal-subtitle" style="font-size:12px; color:var(--text-muted); margin-bottom:20px;"></p>

            <div id="tag-modal-error" style="display:none; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; color:#dc2626; font-size:13px; margin-bottom:16px;"></div>

            <label style="display:block; font-size:13px; font-weight:600; color:var(--text-secondary); margin-bottom:6px;">New Asset Tag</label>
            <input id="tag-modal-input" type="text" maxlength="50" placeholder="e.g. AST-LPT-0099"
                style="width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; font-family:monospace; outline:none; box-sizing:border-box; margin-bottom:20px;"
                onkeydown="if(event.key==='Enter')submitTagChange();">

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button onclick="closeTagModal()" style="padding:9px 20px; border:1px solid var(--border-color); border-radius:8px; font-size:14px; background:#fff; color:var(--text-secondary); cursor:pointer;">Cancel</button>
                <button onclick="submitTagChange()" id="tag-modal-save-btn" style="padding:9px 20px; border:none; border-radius:8px; font-size:14px; font-weight:600; background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; cursor:pointer;">Save Tag</button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="tag-toast" style="display:none; position:fixed; bottom:24px; right:24px; z-index:99999; background:#10b981; color:#fff; padding:14px 20px; border-radius:12px; font-size:14px; font-weight:500; box-shadow:0 4px 20px rgba(0,0,0,.15); max-width:340px;"></div>

    <script>
        let _tagAssetId = null, _tagUrl = null;

        function openTagModal(assetId, currentTag, url) {
            _tagAssetId = assetId;
            _tagUrl = url;
            document.getElementById('tag-modal-input').value = currentTag;
            document.getElementById('tag-modal-subtitle').textContent = 'Asset ID: ' + assetId + ' · Current tag: ' + currentTag;
            document.getElementById('tag-modal-error').style.display = 'none';
            document.getElementById('tag-modal').style.display = 'flex';
            setTimeout(() => document.getElementById('tag-modal-input').select(), 80);
        }

        function closeTagModal() {
            document.getElementById('tag-modal').style.display = 'none';
        }

        function submitTagChange() {
            const newTag = document.getElementById('tag-modal-input').value.trim();
            const errorEl = document.getElementById('tag-modal-error');
            if (!newTag) { showModalError('Asset tag tidak boleh kosong.'); return; }

            const btn = document.getElementById('tag-modal-save-btn');
            btn.disabled = true; btn.textContent = 'Saving…';

            fetch(_tagUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: JSON.stringify({ asset_tag: newTag, _method: 'PATCH' }),
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                btn.disabled = false; btn.textContent = 'Save Tag';
                if (!ok) {
                    const msgs = data.errors?.asset_tag || [data.message || 'Error'];
                    showModalError(msgs.join(' '));
                    return;
                }
                // Update tag label in the table
                const label = document.getElementById('asset-tag-label-' + _tagAssetId);
                if (label) label.textContent = data.asset_tag;
                closeTagModal();
                showToast('Asset tag berhasil diubah menjadi ' + data.asset_tag);
            })
            .catch(() => { btn.disabled = false; btn.textContent = 'Save Tag'; showModalError('Terjadi kesalahan jaringan.'); });
        }

        function showModalError(msg) {
            const el = document.getElementById('tag-modal-error');
            el.textContent = msg; el.style.display = 'block';
        }

        function showToast(msg) {
            const t = document.getElementById('tag-toast');
            t.textContent = msg; t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        // Close modal when clicking outside
        document.getElementById('tag-modal').addEventListener('click', function(e) {
            if (e.target === this) closeTagModal();
        });
    </script>

    {{-- ─── Asset Tag Format Settings Modal ─── --}}
    <div id="fmt-modal" style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(0,0,0,.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; width:480px; max-width:96vw; box-shadow:0 24px 80px rgba(0,0,0,.3); overflow:hidden; position:relative;">

            {{-- Header --}}
            <div style="padding:24px 28px 0;">
                <button onclick="closeFmtModal()" style="position:absolute; top:16px; right:20px; background:none; border:none; font-size:22px; cursor:pointer; color:#94a3b8; line-height:1;">&times;</button>
                <h3 style="font-size:17px; font-weight:700; color:#0f172a; margin:0 0 2px;">Asset Tag Format</h3>
                <p style="font-size:12px; color:#94a3b8; margin:0 0 18px;">Configure how asset tags are generated for new assets</p>
            </div>

            {{-- Live Preview --}}
            <div style="background:#f0f4ff; border-top:1px solid #e0e7ff; border-bottom:1px solid #e0e7ff; padding:16px 28px 18px;">
                <div style="font-size:10px; font-weight:700; letter-spacing:.1em; color:#6366f1; margin-bottom:6px;">PREVIEW — NEXT ASSET TAG</div>
                <div id="fmt-preview" style="font-size:28px; font-weight:800; font-family:monospace; color:#4338ca; letter-spacing:.05em;">ITMS-000001</div>
            </div>

            {{-- Form --}}
            <div style="padding:22px 28px 24px; display:flex; flex-direction:column; gap:20px;">

                {{-- Prefix --}}
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Prefix <span style="color:#ef4444;">*</span></label>
                    <input id="fmt-prefix" type="text" maxlength="20" placeholder="e.g. ITMS"
                        style="width:100%; padding:10px 14px; border:1.5px solid #d1d5db; border-radius:8px; font-size:14px; font-family:monospace; outline:none; box-sizing:border-box; text-transform:uppercase;"
                        oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,''); debouncedPreview()">
                    <p style="font-size:11px; color:#9ca3af; margin-top:4px;">Letters and numbers only. Stored in uppercase.</p>
                </div>

                {{-- Separator --}}
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:10px;">Separator</label>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <label id="sep-dash"   class="sep-option" onclick="selectSep('-')"  style="border:2px solid #6366f1; background:#f0f4ff; color:#4338ca; border-radius:8px; padding:8px 20px; font-size:15px; font-weight:700; cursor:pointer; min-width:52px; text-align:center; transition:all .15s;">-</label>
                        <label id="sep-slash"  class="sep-option" onclick="selectSep('/')"  style="border:2px solid #e5e7eb; background:#fff; color:#374151; border-radius:8px; padding:8px 20px; font-size:15px; font-weight:700; cursor:pointer; min-width:52px; text-align:center; transition:all .15s;">/</label>
                        <label id="sep-under"  class="sep-option" onclick="selectSep('_')"  style="border:2px solid #e5e7eb; background:#fff; color:#374151; border-radius:8px; padding:8px 20px; font-size:15px; font-weight:700; cursor:pointer; min-width:52px; text-align:center; transition:all .15s;">_</label>
                        <label id="sep-none"   class="sep-option" onclick="selectSep('')"   style="border:2px solid #e5e7eb; background:#fff; color:#374151; border-radius:8px; padding:8px 20px; font-size:13px; font-weight:600; cursor:pointer; min-width:52px; text-align:center; transition:all .15s;">None</label>
                    </div>
                    <input type="hidden" id="fmt-separator" value="-">
                </div>

                {{-- Digits --}}
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                        Number of Digits <span style="color:#ef4444;">*</span> <span id="fmt-digits-label" style="color:#6366f1; font-weight:800;">6 digits</span>
                    </label>
                    <input id="fmt-digits" type="range" min="3" max="8" value="6" step="1"
                        style="width:100%; accent-color:#6366f1; cursor:pointer;"
                        oninput="document.getElementById('fmt-digits-label').textContent=this.value+' digits'; document.getElementById('fmt-digits-example').textContent='Example: '+String(1).padStart(parseInt(this.value),'0'); debouncedPreview()">
                    <div style="display:flex; justify-content:space-between; font-size:11px; color:#9ca3af; margin-top:4px;">
                        <span>3</span>
                        <span id="fmt-digits-example">Example: 000001</span>
                        <span>8</span>
                    </div>
                </div>

                {{-- Starting Number --}}
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Starting / Next Number <span style="color:#ef4444;">*</span></label>
                    <input id="fmt-next" type="number" min="1" placeholder="Auto (detect from database)"
                        style="width:100%; padding:10px 14px; border:1.5px solid #d1d5db; border-radius:8px; font-size:14px; outline:none; box-sizing:border-box;"
                        oninput="debouncedPreview()">
                    <p style="font-size:11px; color:#9ca3af; margin-top:4px;">Leave blank to automatically continue from the last tag.</p>
                </div>

                {{-- Error --}}
                <div id="fmt-error" style="display:none; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; color:#dc2626; font-size:13px;"></div>

                {{-- Buttons --}}
                <div style="display:flex; gap:10px; justify-content:flex-end; padding-top:4px;">
                    <button onclick="closeFmtModal()" style="padding:10px 22px; border:1.5px solid #e5e7eb; border-radius:9px; font-size:14px; background:#fff; color:#374151; cursor:pointer; font-weight:500;">Cancel</button>
                    <button onclick="saveFmtSettings()" id="fmt-save-btn" style="padding:10px 22px; border:none; border-radius:9px; font-size:14px; font-weight:700; background:linear-gradient(135deg,#6366f1,#4338ca); color:#fff; cursor:pointer;">Save Format</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ─── Tag Format Settings Modal ───────────────────────────
        const _fmtShowUrl = '{{ route("assets.tag-format.show") }}';
        const _fmtSaveUrl = '{{ route("assets.tag-format.save") }}';

        function openTagFormatModal() {
            // Load current settings via API
            fetch(_fmtShowUrl, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('fmt-prefix').value    = data.prefix    || 'ITMS';
                    document.getElementById('fmt-digits').value    = data.digits    || 6;
                    document.getElementById('fmt-next').value      = data.next_number || '';
                    document.getElementById('fmt-digits-label').textContent = (data.digits || 6) + ' digits';
                    document.getElementById('fmt-digits-example').textContent = 'Example: ' + String(1).padStart(data.digits || 6, '0');

                    // Set separator
                    const sep = data.separator !== undefined ? data.separator : '-';
                    document.getElementById('fmt-separator').value = sep;
                    selectSep(sep, false); // false = don't trigger preview (we'll do it after)

                    // Preview uses computed_next
                    const nextNum = data.next_number || data.computed_next || 1;
                    document.getElementById('fmt-next').placeholder = 'Auto (next: ' + nextNum + ')';

                    updatePreview(data.prefix || 'ITMS', sep, data.digits || 6, nextNum);
                    document.getElementById('fmt-error').style.display = 'none';
                });
            document.getElementById('fmt-modal').style.display = 'flex';
        }

        function closeFmtModal() {
            document.getElementById('fmt-modal').style.display = 'none';
        }

        function selectSep(val, triggerPreview = true) {
            document.getElementById('fmt-separator').value = val;
            const map = { '-': 'sep-dash', '/': 'sep-slash', '_': 'sep-under', '': 'sep-none' };
            ['sep-dash','sep-slash','sep-under','sep-none'].forEach(id => {
                const el = document.getElementById(id);
                el.style.border = '2px solid #e5e7eb';
                el.style.background = '#fff';
                el.style.color = '#374151';
            });
            const active = document.getElementById(map[val]);
            if (active) {
                active.style.border = '2px solid #6366f1';
                active.style.background = '#f0f4ff';
                active.style.color = '#4338ca';
            }
            if (triggerPreview) debouncedPreview();
        }

        function updatePreview(prefix, sep, digits, next) {
            const tag = prefix + sep + String(Math.max(1, parseInt(next) || 1)).padStart(parseInt(digits), '0');
            document.getElementById('fmt-preview').textContent = tag;
        }

        let _previewTimer = null;
        function debouncedPreview() {
            clearTimeout(_previewTimer);
            _previewTimer = setTimeout(() => {
                const prefix = document.getElementById('fmt-prefix').value.trim() || 'ITMS';
                const sep    = document.getElementById('fmt-separator').value;
                const digits = parseInt(document.getElementById('fmt-digits').value) || 6;
                const next   = parseInt(document.getElementById('fmt-next').value) || 1;
                updatePreview(prefix, sep, digits, next);
            }, 200);
        }

        function saveFmtSettings() {
            const prefix = document.getElementById('fmt-prefix').value.trim();
            const sep    = document.getElementById('fmt-separator').value;
            const digits = parseInt(document.getElementById('fmt-digits').value);
            const nextRaw = document.getElementById('fmt-next').value.trim();
            const next   = nextRaw ? parseInt(nextRaw) : null;

            if (!prefix) { showFmtError('Prefix tidak boleh kosong.'); return; }

            const btn = document.getElementById('fmt-save-btn');
            btn.disabled = true; btn.textContent = 'Saving…';
            document.getElementById('fmt-error').style.display = 'none';

            fetch(_fmtSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ prefix, separator: sep, digits, next_number: next }),
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                btn.disabled = false; btn.textContent = 'Save Format';
                if (!ok) {
                    const errs = Object.values(data.errors || {}).flat();
                    showFmtError(errs.join(' ') || data.message || 'Error');
                    return;
                }
                closeFmtModal();
                showToast('Format tag berhasil disimpan. Preview: ' + data.preview);
            })
            .catch(() => { btn.disabled = false; btn.textContent = 'Save Format'; showFmtError('Terjadi kesalahan jaringan.'); });
        }

        function showFmtError(msg) {
            const el = document.getElementById('fmt-error');
            el.textContent = msg; el.style.display = 'block';
        }

        document.getElementById('fmt-modal').addEventListener('click', function(e) {
            if (e.target === this) closeFmtModal();
        });
    </script>
</x-app-layout>
