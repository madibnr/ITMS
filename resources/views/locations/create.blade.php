<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Create Location</h1>
    <div class="panel"><div class="panel-body" style="padding: 24px;">
        <form method="POST" action="{{ route('locations.store') }}">@csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; max-width: 600px;">
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Name *</label><input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Type *</label><select name="type" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">@foreach(['location','building','floor','room'] as $t)<option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Parent Location</label><select name="parent_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"><option value="">None (Top Level)</option>@foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->type }})</option>@endforeach</select></div>
                <div style="grid-column: span 2;"><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Description</label><textarea name="description" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('description') }}</textarea></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Create</button>
                <a href="{{ route('locations.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div></div>
</x-app-layout>
