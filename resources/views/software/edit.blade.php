<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Edit Software</h1>
    <div class="panel"><div class="panel-body" style="padding: 24px;">
        <form method="POST" action="{{ route('software.update', $software) }}">@csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; max-width: 600px;">
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Software Name *</label><input type="text" name="software_name" value="{{ old('software_name', $software->software_name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Vendor</label><input type="text" name="vendor" value="{{ old('vendor', $software->vendor) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Version</label><input type="text" name="version" value="{{ old('version', $software->version) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Category</label><input type="text" name="category" value="{{ old('category', $software->category) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Update</button>
                <a href="{{ route('software.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div></div>
</x-app-layout>
