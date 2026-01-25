@extends('layouts.admin', ['title' => 'User Details'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <a href="{{ route('admin.users.index') }}" style="background: var(--glass); color: var(--light); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid var(--glass-border); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.borderColor='var(--glass-border)'; this.style.background='var(--glass)'">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(255, 68, 68, 0.2); border: 1px solid #ff4444; color: #ff4444; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 30px; border: 1px solid var(--glass-border); max-width: 900px; margin: 0 auto;">
        <!-- User Header -->
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--glass-border);">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent);">
            @else
                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent-glow)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 2rem; border: 3px solid var(--accent);">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif
            <div style="flex: 1;">
                <h2 style="font-family: 'Orbitron', sans-serif; color: var(--accent); font-size: 1.8rem; margin-bottom: 10px;">{{ $user->name }}</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                    @if($user->is_verified)
                        <span style="padding: 6px 12px; border-radius: 15px; background: rgba(0, 204, 102, 0.2); color: var(--success); font-size: 0.85rem; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @else
                        <span style="padding: 6px 12px; border-radius: 15px; background: rgba(255, 165, 0, 0.2); color: #ffa500; font-size: 0.85rem; font-weight: 600;">
                            <i class="fas fa-clock"></i> Pending Verification
                        </span>
                    @endif
                    <span style="padding: 6px 12px; border-radius: 15px; background: rgba(255,255,255,0.1); color: var(--light); text-transform: uppercase; font-size: 0.85rem; font-weight: 600;">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 8px;">Email Address</div>
                <div style="color: var(--light); font-weight: 600; font-size: 1.1rem;">{{ $user->email }}</div>
            </div>
            
            <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 8px;">Phone Number</div>
                <div style="color: var(--light); font-weight: 600; font-size: 1.1rem;">{{ $user->phone ?? 'Not provided' }}</div>
            </div>
            
            <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 8px;">Member Since</div>
                <div style="color: var(--light); font-weight: 600; font-size: 1.1rem;">{{ $user->created_at->format('F d, Y') }}</div>
            </div>
            
            <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border);">
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 8px;">Last Active</div>
                <div style="color: var(--light); font-weight: 600; font-size: 1.1rem;">{{ $user->updated_at->diffForHumans() }}</div>
            </div>
        </div>

        @if($user->bio)
        <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border); margin-bottom: 30px;">
            <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 10px;">Bio</div>
            <div style="color: var(--light); line-height: 1.6;">{{ $user->bio }}</div>
        </div>
        @endif

        <!-- User Statistics -->
        <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; border: 1px solid var(--glass-border); margin-bottom: 30px;">
            <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 20px; font-size: 1.2rem;">User Activity</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 5px;">Comments</div>
                    <div style="color: var(--light); font-weight: 700; font-size: 1.5rem;">{{ $user->comments()->count() }}</div>
                </div>
                <div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 5px;">Likes Given</div>
                    <div style="color: var(--light); font-weight: 700; font-size: 1.5rem;">{{ \DB::table('post_likes')->where('user_id', $user->id)->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--glass-border);">
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user: {{ $user->name }}? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: transparent; border: 1px solid var(--accent); color: var(--accent); padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.2)'; this.style.borderColor='#ff4444'; this.style.color='#ff4444'" onmouseout="this.style.background='transparent'; this.style.borderColor='var(--accent)'; this.style.color='var(--accent)'">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            </form>
        </div>
    </div>
@endsection

