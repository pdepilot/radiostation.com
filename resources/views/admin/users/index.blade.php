@extends('layouts.admin', ['title' => 'Users Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 400px;">
            <div style="position: relative; flex: 1;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search users by name, email, or phone..." 
                       style="width: 100%; padding: 12px 40px 12px 15px; background: rgba(0,0,0,0.4); border: 1px solid var(--glass-border); border-radius: 25px; color: var(--light); outline: none; font-size: 0.9rem; transition: all 0.3s;"
                       onfocus="this.style.borderColor='var(--accent)'; this.style.background='rgba(0,0,0,0.6)'"
                       onblur="this.style.borderColor='var(--glass-border)'; this.style.background='rgba(0,0,0,0.4)'">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;"></i>
            </div>
            @if($search)
                <a href="{{ route('admin.users.index') }}" style="padding: 12px 20px; background: rgba(255,0,0,0.2); border: 1px solid var(--accent); border-radius: 25px; color: var(--accent); text-decoration: none; transition: all 0.3s; font-size: 0.9rem;" 
                   onmouseover="this.style.background='rgba(255,0,0,0.3)'" onmouseout="this.style.background='rgba(255,0,0,0.2)'">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>

    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--accent); font-weight: 700;">{{ $users->total() }}</div>
            <div style="color: var(--text-secondary);">Total Users</div>
        </div>
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--success); font-weight: 700;">{{ $users->where('is_verified', true)->count() }}</div>
            <div style="color: var(--text-secondary);">Verified</div>
        </div>
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: #ffa500; font-weight: 700;">{{ $users->where('is_verified', false)->count() }}</div>
            <div style="color: var(--text-secondary);">Unverified</div>
        </div>
    </div>

    <!-- Users List -->
    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--glass-border);">
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Name</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Email</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Phone</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Role</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Status</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Joined</th>
                        <th style="padding: 15px; text-align: right; color: var(--accent); font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid var(--glass-border); transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <td data-label="Name" style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: var(--light);">{{ $user->name }}</div>
                                        @if($user->bio)
                                            <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ Str::limit($user->bio, 30) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td data-label="Email" style="padding: 15px; color: var(--light);">{{ $user->email }}</td>
                            <td data-label="Phone" style="padding: 15px; color: var(--text-secondary);">{{ $user->phone ?? 'N/A' }}</td>
                            <td data-label="Role" style="padding: 15px;">
                                <span style="padding: 4px 10px; border-radius: 15px; background: rgba(255,255,255,0.1); color: var(--light); text-transform: uppercase; font-size: 0.75rem; font-weight: 600;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td data-label="Status" style="padding: 15px;">
                                @if($user->is_verified)
                                    <span style="padding: 4px 10px; border-radius: 15px; background: rgba(0, 204, 102, 0.2); color: var(--success); font-size: 0.75rem; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                @else
                                    <span style="padding: 4px 10px; border-radius: 15px; background: rgba(255, 165, 0, 0.2); color: #ffa500; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td data-label="Joined" style="padding: 15px; color: var(--text-secondary); font-size: 0.9rem;">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td data-label="Actions" style="padding: 15px; text-align: right;">
                                <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                                    <a href="{{ route('admin.users.show', $user) }}" style="color: var(--accent); text-decoration: none; padding: 6px 12px; border: 1px solid var(--accent); border-radius: 5px; transition: all 0.3s; font-size: 0.85rem;" onmouseover="this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.background='transparent'" title="View Details">
                                        <i class="fas fa-eye"></i> <span class="mobile-hide">View</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user: {{ $user->name }}? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: transparent; border: 1px solid var(--accent); color: var(--accent); padding: 6px 12px; border-radius: 5px; cursor: pointer; transition: all 0.3s; font-size: 0.85rem;" onmouseover="this.style.background='rgba(255,0,0,0.2)'; this.style.borderColor='#ff4444'; this.style.color='#ff4444'" onmouseout="this.style.background='transparent'; this.style.borderColor='var(--accent)'; this.style.color='var(--accent)'" title="Delete User">
                                            <i class="fas fa-trash"></i> <span class="mobile-hide">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 60px; text-align: center; color: var(--text-secondary);">
                                <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3; margin-bottom: 20px;"></i>
                                <p>No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $users->links() }}
        </div>
    </div>
@endsection

