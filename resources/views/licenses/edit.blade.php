<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Edit License</h1>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="panel"><div class="panel-body" style="padding: 24px;">
            <form method="POST" action="{{ route('licenses.update', $license) }}">@csrf @method('PUT')
                <div style="display: grid; gap: 16px;">
                    <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Software *</label><select name="software_id" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;">@foreach($software as $s)<option value="{{ $s->id }}" {{ old('software_id', $license->software_id) == $s->id ? 'selected' : '' }}>{{ $s->software_name }}</option>@endforeach</select></div>
                    <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">License Key *</label><input type="text" name="license_key" value="{{ old('license_key', $license->license_key) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; font-family: monospace;"></div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Seats *</label><input type="number" name="seats" value="{{ old('seats', $license->seats) }}" min="1" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                        <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Expiration Date</label><input type="date" name="expiration_date" value="{{ old('expiration_date', $license->expiration_date?->format('Y-m-d')) }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px;"></div>
                    </div>
                    <div><label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px;">Notes</label><textarea name="notes" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('notes', $license->notes) }}</textarea></div>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="submit" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">Update</button>
                    <a href="{{ route('licenses.index') }}" style="padding: 10px 24px; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-secondary); font-size: 14px;">Cancel</a>
                </div>
            </form>
        </div></div>

        {{-- User Assignment Panel --}}
        <div class="panel"><div class="panel-body" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Assigned Users ({{ $license->users->count() }} / {{ $license->seats }})</h3>

            @if($license->available_seats > 0)
            <form method="POST" action="{{ route('licenses.assign-user', $license) }}" style="display: flex; gap: 8px; margin-bottom: 16px;">
                @csrf
                <select name="user_id" required style="flex: 1; padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px;">
                    <option value="">Select User</option>
                    @foreach($users as $u)
                        @unless($license->users->contains($u->id))<option value="{{ $u->id }}">{{ $u->name }}</option>@endunless
                    @endforeach
                </select>
                <button type="submit" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 8px; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">Assign</button>
            </form>
            @else
            <div style="background: #fef3c7; padding: 10px 16px; border-radius: 8px; font-size: 13px; color: #92400e; margin-bottom: 16px;">All seats are in use.</div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 8px;">
                @forelse($license->users as $u)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    <span style="font-weight: 500;">{{ $u->name }}</span>
                    <form method="POST" action="{{ route('licenses.revoke-user', [$license, $u]) }}">@csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Revoke this user?')" style="color: #ef4444; font-size: 12px; font-weight: 600; background: none; border: none; cursor: pointer;">Revoke</button>
                    </form>
                </div>
                @empty <p style="color: var(--text-muted); font-size: 13px;">No users assigned.</p>
                @endforelse
            </div>
        </div></div>
    </div>
</x-app-layout>
