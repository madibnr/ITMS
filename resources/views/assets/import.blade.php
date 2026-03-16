<x-app-layout>
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Import Assets</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">Upload an Excel file (.xlsx, .xls, .csv) to bulk import assets.</p>

        <div class="panel">
            <div class="panel-body" style="padding: 24px;">
                <form method="POST" action="{{ route('assets.import.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Excel File *</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">
                        @error('file') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>

                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                        <p style="font-size: 13px; font-weight: 600; color: #0369a1; margin-bottom: 8px;">Expected Columns:</p>
                        <p style="font-size: 12px; color: #0c4a6e; font-family: monospace;">name, category, brand, model, serial_number, purchase_date, purchase_cost, supplier, warranty_expiration, location, status, notes</p>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="{{ route('assets.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
                        <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; cursor: pointer;">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
