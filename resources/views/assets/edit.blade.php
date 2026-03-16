<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Edit Asset</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">{{ $asset->asset_tag ?? $asset->asset_code }} — {{ $asset->name }}</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body" style="padding: 24px;">
            <form method="POST" action="{{ route('assets.update', $asset) }}">
                @csrf @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $asset->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Category *</label>
                        <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Asset Model</label>
                        <select name="model_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">Select Model (optional)</option>
                            @foreach($assetModels as $m)<option value="{{ $m->id }}" {{ old('model_id', $asset->model_id) == $m->id ? 'selected' : '' }}>{{ $m->manufacturer->name ?? '' }} {{ $m->model_name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand', $asset->brand) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Model (manual)</label>
                        <input type="text" name="model" value="{{ old('model', $asset->model) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Supplier</label>
                        <input type="text" name="supplier" value="{{ old('supplier', $asset->supplier) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Purchase Cost</label>
                        <input type="number" step="0.01" name="purchase_cost" value="{{ old('purchase_cost', $asset->purchase_cost) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Warranty Expiration</label>
                        <input type="date" name="warranty_expiration" value="{{ old('warranty_expiration', ($asset->warranty_expiration ?? $asset->warranty_expired)?->format('Y-m-d')) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Location</label>
                        <select name="location_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">Select Location</option>
                            @foreach($locations as $loc)<option value="{{ $loc->id }}" {{ old('location_id', $asset->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->type }})</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Status *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            @foreach(\App\Models\Asset::STATUSES as $s)<option value="{{ $s }}" {{ old('status', $asset->status) == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Assigned User</label>
                        <select name="assigned_user_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">None</option>
                            @foreach($users as $user)<option value="{{ $user->id }}" {{ old('assigned_user_id', $asset->assigned_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Notes</label>
                    <textarea name="notes" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('notes', $asset->notes) }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('assets.show', $asset) }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">Cancel</a>
                    <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Update Asset</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
