<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Create Manufacturer</h1>
    <div class="panel"><div class="panel-body" style="padding: 24px;">
        <form method="POST" action="{{ route('manufacturers.store') }}">@csrf
            <div style="display: grid; gap: 16px; max-width: 600px;">
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Name *</label><input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">@error('name')<span style="color:#ef4444;font-size:12px;">{{ $message }}</span>@enderror</div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Website</label><input type="url" name="website" value="{{ old('website') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Support Email</label><input type="email" name="support_email" value="{{ old('support_email') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;"></div>
                <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Notes</label><textarea name="notes" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('notes') }}</textarea></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Create</button>
                <a href="{{ route('manufacturers.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div></div>
</x-app-layout>
