<x-app-layout>
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Asset QR Code</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">{{ $asset->asset_tag ?? $asset->asset_code }} — {{ $asset->name }}</p>

        <div class="panel" style="padding: 40px; display: inline-block;">
            <div style="margin-bottom: 20px;">{!! $qrCode !!}</div>
            <div style="font-family: monospace; font-size: 18px; font-weight: 700; color: var(--text-primary);">{{ $asset->asset_tag ?? $asset->asset_code }}</div>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">{{ $asset->name }}</div>
            <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">{{ $asset->display_model }}</div>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: center;">
            <button onclick="window.print()" style="background: var(--primary); color: white; padding: 10px 24px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; cursor: pointer;">Print</button>
            <a href="{{ route('assets.show', $asset) }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Back to Asset</a>
        </div>
    </div>
</x-app-layout>
