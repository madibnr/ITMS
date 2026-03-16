<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">New Category</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                <a href="{{ route('doc-categories.index') }}" style="color: var(--text-muted); text-decoration: none;">Categories</a> / Create
            </p>
        </div>
    </div>

    <div style="max-width: 560px;">
        <div class="panel">
            <div class="panel-body" style="padding: 24px;">
                <form method="POST" action="{{ route('doc-categories.store') }}">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Network Documentation" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                        @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Icon (Emoji)</label>
                        <input type="text" name="icon" value="{{ old('icon') }}" placeholder="e.g. 🌐" maxlength="10" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Optional emoji icon for visual identification.</div>
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Description</label>
                        <textarea name="description" rows="3" maxlength="500" placeholder="Brief description of this category…" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; resize: vertical;">{{ old('description') }}</textarea>
                        @error('description') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('doc-categories.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">Cancel</a>
                        <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
