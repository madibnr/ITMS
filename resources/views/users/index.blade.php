<x-app-layout>
    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">User Management</h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Kelola akun dan hak akses pengguna sistem</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('users.export') }}" class="badge badge-open" style="padding: 8px 16px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('users.create') }}" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 8px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New User
            </a>
        </div>
    </div>

    {{-- Search / Filter --}}
    <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-body" style="padding: 16px 24px;">
            <form method="GET" action="{{ route('users.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; width: 220px; outline: none;">
                <select name="role" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <select name="status" style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" style="background: var(--text-primary); color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">Filter</button>
                <a href="{{ route('users.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none;">Clear</a>
            </form>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="panel">
        <div class="panel-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-light), var(--accent)); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; flex-shrink: 0;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span style="font-weight: 600; color: var(--text-primary);">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary); font-size: 13px;">{{ $user->email }}</td>
                            <td style="color: var(--text-muted); font-size: 13px;">{{ $user->department ?? '-' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-open" style="font-size: 11px; padding: 2px 8px; margin-right: 2px;">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('users.edit', $user) }}" style="color: var(--primary); font-size: 12px; font-weight: 600; text-decoration: none; padding: 4px 10px; border: 1px solid #c7d2fe; border-radius: 6px; margin-right: 4px;">Edit</a>
                                <form method="POST" action="{{ route('users.toggle-active', $user) }}" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer; border: 1px solid {{ $user->is_active ? '#fca5a5' : '#6ee7b7' }}; background: none; color: {{ $user->is_active ? '#ef4444' : '#059669' }}; margin-right: 4px;">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus user {{ addslashes($user->name) }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer; border: 1px solid #fca5a5; background: none; color: #ef4444;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 16px;">
                                No users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
