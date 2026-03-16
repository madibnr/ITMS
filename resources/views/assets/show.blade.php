<x-app-layout>
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">{{ $asset->asset_tag ?? $asset->asset_code }}</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">{{ $asset->name }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('assets.qrcode', $asset) }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px;">QR Code</a>
            <a href="{{ route('assets.edit', $asset) }}" style="background: var(--text-primary); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600;">Edit</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        {{-- Main Content --}}
        <div>
            {{-- Tab Navigation --}}
            <div x-data="{ tab: 'info' }" style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; gap: 4px; border-bottom: 2px solid var(--border-color); padding-bottom: 0;">
                    <button @click="tab = 'info'" :style="tab === 'info' ? 'border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600;' : 'color: var(--text-muted);'" style="padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; margin-bottom: -2px;">General Info</button>
                    <button @click="tab = 'assignments'" :style="tab === 'assignments' ? 'border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600;' : 'color: var(--text-muted);'" style="padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; margin-bottom: -2px;">Assignments ({{ $asset->assignments->count() }})</button>
                    <button @click="tab = 'maintenance'" :style="tab === 'maintenance' ? 'border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600;' : 'color: var(--text-muted);'" style="padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; margin-bottom: -2px;">Maintenance ({{ $asset->maintenanceLogs->count() }})</button>
                    <button @click="tab = 'files'" :style="tab === 'files' ? 'border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600;' : 'color: var(--text-muted);'" style="padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; margin-bottom: -2px;">Files ({{ $asset->files->count() }})</button>
                    <button @click="tab = 'logs'" :style="tab === 'logs' ? 'border-bottom: 2px solid var(--primary); color: var(--primary); font-weight: 600;' : 'color: var(--text-muted);'" style="padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; margin-bottom: -2px;">Audit Log</button>
                </div>

                {{-- Tab: General Info --}}
                <div x-show="tab === 'info'" class="panel">
                    <div class="panel-body" style="padding: 24px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Model</span><p style="font-weight: 500;">{{ $asset->display_model }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Serial Number</span><p style="font-weight: 500; font-family: monospace;">{{ $asset->serial_number ?? '-' }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Location</span><p style="font-weight: 500;">{{ $asset->display_location }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Supplier</span><p style="font-weight: 500;">{{ $asset->supplier ?? '-' }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Purchase Date</span><p style="font-weight: 500;">{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Purchase Cost</span><p style="font-weight: 500;">{{ $asset->purchase_cost ? 'Rp ' . number_format($asset->purchase_cost, 0, ',', '.') : '-' }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Warranty</span><p style="font-weight: 500; {{ $asset->isWarrantyExpired() ? 'color: #ef4444;' : '' }}">{{ $asset->warranty_date?->format('d M Y') ?? '-' }}</p></div>
                            <div><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Depreciation</span><p style="font-weight: 500;">Rp {{ number_format($depreciation['current_value'], 0, ',', '.') }} ({{ $depreciation['age_years'] }}y)</p></div>
                        </div>
                        @if($asset->notes)<div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-color);"><span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Notes</span><p style="margin-top: 4px; color: var(--text-secondary);">{{ $asset->notes }}</p></div>@endif
                    </div>
                </div>

                {{-- Tab: Assignments --}}
                <div x-show="tab === 'assignments'" style="display: flex; flex-direction: column; gap: 16px;">
                    @if(!$asset->assigned_user_id)
                    <div class="panel"><div class="panel-body" style="padding: 20px;">
                        <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Assign Asset</h3>
                        <form method="POST" action="{{ route('assets.assign', $asset) }}" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                            @csrf
                            <div><label style="font-size: 12px; color: var(--text-muted);">User *</label><select name="assigned_to_user_id" required style="padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px;">
                                <option value="">Select User</option>
                                @foreach(\App\Models\User::where('is_active', true)->get() as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                            </select></div>
                            <div><label style="font-size: 12px; color: var(--text-muted);">Expected Return</label><input type="date" name="expected_return_date" style="padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px;"></div>
                            <div><label style="font-size: 12px; color: var(--text-muted);">Notes</label><input type="text" name="notes" style="padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px;"></div>
                            <button type="submit" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">Assign</button>
                        </form>
                    </div></div>
                    @else
                    <div class="panel"><div class="panel-body" style="padding: 20px;">
                        <form method="POST" action="{{ route('assets.return', $asset) }}" style="display: flex; justify-content: space-between; align-items: center;">
                            @csrf
                            <span style="color: var(--text-secondary);">Currently assigned to <strong>{{ $asset->assignedUser->name }}</strong></span>
                            <button type="submit" style="background: #f59e0b; color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">Return Asset</button>
                        </form>
                    </div></div>
                    @endif

                    <div class="panel"><div class="panel-body" style="padding: 0;">
                        <table class="data-table">
                            <thead><tr><th>User</th><th>Assigned By</th><th>Date</th><th>Expected Return</th><th>Returned</th><th>Notes</th></tr></thead>
                            <tbody>
                                @forelse($asset->assignments as $a)
                                <tr>
                                    <td style="font-weight: 500;">{{ $a->assignedTo->name ?? '-' }}</td>
                                    <td>{{ $a->assignedByUser->name ?? '-' }}</td>
                                    <td>{{ $a->assigned_date->format('d M Y') }}</td>
                                    <td>{{ $a->expected_return_date?->format('d M Y') ?? '-' }}</td>
                                    <td>{!! $a->returned_date ? '<span class="badge badge-active">' . $a->returned_date->format('d M Y') . '</span>' : '<span class="badge badge-in-progress">Active</span>' !!}</td>
                                    <td style="color: var(--text-muted); font-size: 13px;">{{ $a->notes ?? '-' }}</td>
                                </tr>
                                @empty <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 24px;">No assignment history.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div></div>
                </div>

                {{-- Tab: Maintenance --}}
                <div x-show="tab === 'maintenance'" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('asset-maintenance.create', ['asset_id' => $asset->id]) }}" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">+ Add Maintenance</a>
                    </div>
                    <div class="panel"><div class="panel-body" style="padding: 0;">
                        <table class="data-table">
                            <thead><tr><th>Type</th><th>Vendor</th><th>Cost</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($asset->maintenanceLogs as $m)
                                <tr>
                                    <td style="font-weight: 500;">{{ $m->maintenance_type }}</td>
                                    <td>{{ $m->vendor ?? '-' }}</td>
                                    <td>{{ $m->cost ? 'Rp ' . number_format($m->cost, 0, ',', '.') : '-' }}</td>
                                    <td>{{ $m->start_date->format('d M Y') }}</td>
                                    <td>{{ $m->end_date?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        @php $mc = match($m->status) { 'Completed' => 'badge-active', 'In Progress' => 'badge-in-progress', default => 'badge-open' }; @endphp
                                        <span class="badge {{ $mc }}">{{ $m->status }}</span>
                                    </td>
                                </tr>
                                @empty <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 24px;">No maintenance records.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div></div>
                </div>

                {{-- Tab: Files --}}
                <div x-show="tab === 'files'" style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="panel"><div class="panel-body" style="padding: 20px;">
                        <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Upload File</h3>
                        <form method="POST" action="{{ route('asset-files.store', $asset) }}" enctype="multipart/form-data" style="display: flex; gap: 12px; align-items: flex-end;">
                            @csrf
                            <input type="file" name="file" required style="padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px;">
                            <button type="submit" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">Upload</button>
                        </form>
                    </div></div>

                    <div class="panel"><div class="panel-body" style="padding: 0;">
                        <table class="data-table">
                            <thead><tr><th>File</th><th>Type</th><th>Size</th><th>Uploaded By</th><th>Date</th><th>Action</th></tr></thead>
                            <tbody>
                                @forelse($asset->files as $f)
                                <tr>
                                    <td style="font-weight: 500;">{{ $f->file_name }}</td>
                                    <td>{{ $f->file_type ?? '-' }}</td>
                                    <td>{{ $f->human_size }}</td>
                                    <td>{{ $f->uploader->name ?? '-' }}</td>
                                    <td>{{ $f->created_at->format('d M Y') }}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('asset-files.download', $f) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; margin-right: 8px;">Download</a>
                                        <form method="POST" action="{{ route('asset-files.destroy', $f) }}" style="display: inline;">@csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this file?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: none; cursor: pointer;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 24px;">No files attached.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div></div>
                </div>

                {{-- Tab: Audit Log --}}
                <div x-show="tab === 'logs'">
                    <div class="panel"><div class="panel-body" style="padding: 20px;">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @forelse($asset->auditLogs as $log)
                            <div style="display: flex; justify-content: space-between; align-items: start; border-left: 3px solid var(--primary); padding-left: 12px;">
                                <div>
                                    <span style="font-weight: 600; font-size: 13px;">{{ $log->action }}</span>
                                    <span style="color: var(--text-muted); font-size: 12px; margin-left: 8px;">by {{ $log->user->name ?? 'System' }}</span>
                                    @if($log->changes)<pre style="font-size: 11px; color: var(--text-muted); margin-top: 4px; white-space: pre-wrap;">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>@endif
                                </div>
                                <span style="font-size: 12px; color: var(--text-muted); white-space: nowrap;">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            @empty <p style="text-align: center; color: var(--text-muted); padding: 24px;">No audit logs.</p>
                            @endforelse
                        </div>
                    </div></div>
                </div>
            </div>
        </div>

        {{-- Sidebar Details --}}
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div class="panel"><div class="panel-body" style="padding: 20px;">
                <h4 style="font-weight: 600; margin-bottom: 12px;">Details</h4>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                    <div><span style="color: var(--text-muted); font-size: 12px;">Status</span>
                        @php $bc = match($asset->status) { 'Active' => 'badge-active', 'In Repair' => 'badge-in-progress', 'In Stock' => 'badge-open', default => 'badge-closed' }; @endphp
                        <div><span class="badge {{ $bc }}">{{ $asset->status }}</span></div>
                    </div>
                    <div><span style="color: var(--text-muted); font-size: 12px;">Category</span><div>{{ $asset->category->name ?? '-' }}</div></div>
                    <div><span style="color: var(--text-muted); font-size: 12px;">Assigned To</span><div style="font-weight: 500;">{{ $asset->assignedUser->name ?? 'Unassigned' }}</div></div>
                    <div><span style="color: var(--text-muted); font-size: 12px;">Asset Code</span><div style="font-family: monospace;">{{ $asset->asset_code }}</div></div>
                </div>
            </div></div>

            {{-- Delete --}}
            <div class="panel"><div class="panel-body" style="padding: 20px;">
                <form method="POST" action="{{ route('assets.destroy', $asset) }}">@csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure? This will delete the asset.')" style="width: 100%; background: #fef2f2; color: #ef4444; padding: 10px; border-radius: 8px; border: 1px solid #fecaca; font-weight: 600; cursor: pointer; font-size: 13px;">Delete Asset</button>
                </form>
            </div></div>
        </div>
    </div>
</x-app-layout>
