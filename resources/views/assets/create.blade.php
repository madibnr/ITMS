<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Create Asset</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Add a new asset to inventory</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body" style="padding: 24px;">
            <form method="POST" action="{{ route('assets.store') }}">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                        @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Category *</label>
                        <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Asset Model</label>
                        <select name="model_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">Select Model (optional)</option>
                            @foreach($assetModels as $m)<option value="{{ $m->id }}" {{ old('model_id') == $m->id ? 'selected' : '' }}>{{ $m->manufacturer->name ?? '' }} {{ $m->model_name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                        @error('serial_number') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Model (manual)</label>
                        <input type="text" name="model" value="{{ old('model') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Supplier</label>
                        <input type="text" name="supplier" value="{{ old('supplier') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Purchase Cost</label>
                        <input type="number" step="0.01" name="purchase_cost" value="{{ old('purchase_cost') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Warranty Expiration</label>
                        <input type="date" name="warranty_expiration" value="{{ old('warranty_expiration') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Location</label>
                        <select name="location_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">Select Location</option>
                            @foreach($locations as $loc)<option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->type }})</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Status *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            @foreach(\App\Models\Asset::STATUSES as $s)<option value="{{ $s }}" {{ old('status', 'In Stock') == $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Assigned User</label>
                        <select name="assigned_user_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                            <option value="">None</option>
                            @foreach($users as $user)<option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Notes</label>
                    <textarea name="notes" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('assets.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">Cancel</a>
                    <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Create Asset</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
