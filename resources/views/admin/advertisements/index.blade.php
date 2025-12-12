@extends('layouts.admin', ['title' => 'Featured Sponsors Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <a href="{{ route('admin.adverts.create') }}" class="btn-primary" style="background: var(--accent); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='var(--accent-glow)'" onmouseout="this.style.background='var(--accent)'">
            <i class="fas fa-plus"></i> Add New Sponsor
        </a>
    </div>

    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--accent); font-weight: 700;">{{ $adverts->total() }}</div>
            <div style="color: var(--text-secondary);">Total Sponsors</div>
        </div>
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--success); font-weight: 700;">{{ $adverts->where('is_active', true)->count() }}</div>
            <div style="color: var(--text-secondary);">Active</div>
        </div>
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--accent); font-weight: 700;">{{ number_format($adverts->sum('view_count')) }}</div>
            <div style="color: var(--text-secondary);">Total Views</div>
        </div>
        <div style="background: var(--glass); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border);">
            <div style="font-size: 2rem; color: var(--accent); font-weight: 700;">{{ number_format($adverts->sum('click_count')) }}</div>
            <div style="color: var(--text-secondary);">Total Clicks</div>
        </div>
    </div>

    <!-- Adverts List -->
    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border);">
        @forelse($adverts as $advert)
            <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 10px; margin-bottom: 15px; border: 1px solid var(--glass-border);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <h3 style="color: var(--accent); margin: 0;">{{ $advert->title }}</h3>
                            @if($advert->is_active)
                                <span style="background: var(--success); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">Active</span>
                            @else
                                <span style="background: #666; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">Inactive</span>
                            @endif
                            <span style="background: var(--accent); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">{{ ucfirst($advert->type) }}</span>
                            <span style="background: rgba(255,255,255,0.1); color: var(--light); padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">{{ ucfirst($advert->position) }}</span>
                        </div>
                        @if($advert->description)
                            <p style="color: var(--light); opacity: 0.8; margin-bottom: 10px;">{{ $advert->description }}</p>
                        @endif
                        <div style="display: flex; gap: 20px; color: var(--text-secondary); font-size: 0.9rem;">
                            <span><i class="far fa-eye"></i> {{ number_format($advert->view_count) }} views</span>
                            <span><i class="fas fa-mouse-pointer"></i> {{ number_format($advert->click_count) }} clicks</span>
                            @if($advert->click_count > 0)
                                <span>CTR: {{ number_format(($advert->click_count / max($advert->view_count, 1)) * 100, 2) }}%</span>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('admin.adverts.edit', $advert) }}" style="background: var(--success); color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.85rem;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.adverts.destroy', $advert) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this advertisement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: var(--accent); color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px; color: var(--text-secondary);">
                <i class="fas fa-handshake" style="font-size: 3rem; opacity: 0.3; margin-bottom: 20px;"></i>
                <p>No featured sponsors yet. Add your first sponsor above.</p>
            </div>
        @endforelse

        {{ $adverts->links('pagination::custom') }}
    </div>
@endsection

