<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Create Asset Model</h1>
    <div class="panel"><div class="panel-body" style="padding: 24px;">
        <form method="POST" action="{{ route('asset-models.store') }}" enctype="multipart/form-data">@csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; max-width: 700px;">
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Manufacturer *</label><select name="manufacturer_id" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"><option value="">Select</option>@foreach($manufacturers as $m)<option value="{{ $m->id }}" {{ old('manufacturer_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>@endforeach</select></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Model Name *</label><input type="text" name="model_name" value="{{ old('model_name') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Category *</label><select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"><option value="">Select</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Default Warranty (months)</label><input type="number" name="default_warranty_months" value="{{ old('default_warranty_months') }}" min="0" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                <div style="grid-column: span 2;"><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Image</label><input type="file" name="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Create</button>
                <a href="{{ route('asset-models.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div></div>
</x-app-layout>
