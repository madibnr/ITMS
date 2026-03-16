<x-app-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Edit Tag</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                <a href="{{ route('doc-tags.index') }}" style="color: var(--text-muted); text-decoration: none;">Tags</a> / Edit
            </p>
        </div>
    </div>

    <div style="max-width: 400px;">
        <div class="panel">
            <div class="panel-body" style="padding: 24px;">
                <form method="POST" action="{{ route('doc-tags.update', $tag) }}">
                    @csrf @method('PUT')
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Tag Name *</label>
                        <input type="text" name="name" value="{{ old('name', $tag->name) }}" required autofocus style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none;">
                        @error('name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('doc-tags.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; text-decoration: none; color: var(--text-secondary);">Cancel</a>
                        <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Update Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
