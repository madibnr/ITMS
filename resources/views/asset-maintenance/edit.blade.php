<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Edit Maintenance Record</h1>
    <div class="panel"><div class="panel-body" style="padding: 24px;">
        <form method="POST" action="{{ route('asset-maintenance.update', $assetMaintenance) }}">@csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; max-width: 700px;">
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Maintenance Type *</label><input type="text" name="maintenance_type" value="{{ old('maintenance_type', $assetMaintenance->maintenance_type) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Vendor</label><input type="text" name="vendor" value="{{ old('vendor', $assetMaintenance->vendor) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Cost</label><input type="number" step="0.01" name="cost" value="{{ old('cost', $assetMaintenance->cost) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Start Date *</label><input type="date" name="start_date" value="{{ old('start_date', $assetMaintenance->start_date->format('Y-m-d')) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">End Date</label><input type="date" name="end_date" value="{{ old('end_date', $assetMaintenance->end_date?->format('Y-m-d')) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Status *</label><select name="status" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">@foreach(['Scheduled','In Progress','Completed'] as $s)<option value="{{ $s }}" {{ old('status', $assetMaintenance->status) == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach</select></div>
                <div style="grid-column: span 2;"><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Description</label><textarea name="description" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('description', $assetMaintenance->description) }}</textarea></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Update</button>
                <a href="{{ route('asset-maintenance.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div></div>
</x-app-layout>
