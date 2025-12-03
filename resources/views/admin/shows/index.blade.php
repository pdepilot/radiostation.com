@extends('layouts.admin', ['title' => 'Shows Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem;">Shows Management</h2>
        <a href="{{ route('admin.shows.create') }}" style="background: var(--accent); color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> New Show
        </a>
    </div>

    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 20px; border: 1px solid var(--glass-border);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Title</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Host</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Schedule</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Listeners</th>
                        <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 600;">Status</th>
                        <th style="padding: 15px; text-align: right; color: var(--accent); font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shows as $show)
                        <tr style="border-bottom: 1px solid var(--glass-border); transition: all 0.3s;">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600;">{{ $show->title }}</div>
                                <div style="font-size: 0.85rem; opacity: 0.7;">{{ Str::limit($show->description, 50) }}</div>
                            </td>
                            <td style="padding: 15px;">{{ $show->dj?->stage_name ?? $show->dj?->name ?? 'TBA' }}</td>
                            <td style="padding: 15px;">
                                <div>{{ $show->day_of_week }}</div>
                                <div style="font-size: 0.85rem; opacity: 0.7;">{{ $show->start_time }} - {{ $show->end_time }}</div>
                            </td>
                            <td style="padding: 15px;">{{ number_format($show->listener_count ?? 0) }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 5px 10px; border-radius: 5px; background: rgba(0, 255, 0, 0.2); color: var(--live-green); text-transform: uppercase; font-size: 0.75rem;">
                                    Active
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <a href="{{ route('admin.shows.edit', $show) }}" style="color: var(--info); text-decoration: none; padding: 5px 10px; border: 1px solid var(--info); border-radius: 5px; transition: all 0.3s;" onmouseover="this.style.background='rgba(0, 153, 255, 0.2)'" onmouseout="this.style.background='transparent'">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.shows.destroy', $show) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this show?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: transparent; border: 1px solid var(--accent); color: var(--accent); padding: 5px 10px; border-radius: 5px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='rgba(255, 0, 0, 0.2)'" onmouseout="this.style.background='transparent'">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $shows->links() }}
        </div>
    </div>
@endsection
