@extends('layouts.admin', ['title' => 'Events Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <a href="{{ route('admin.events.create') }}" style="background: var(--accent); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='var(--accent-glow)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
            <i class="fas fa-plus"></i> Create New Event
        </a>
    </div>

    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        @if($events->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--glass-border);">
                            <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Title</th>
                            <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Venue</th>
                            <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Date</th>
                            <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                            <th style="padding: 15px; text-align: left; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Featured</th>
                            <th style="padding: 15px; text-align: right; color: var(--accent); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr style="border-bottom: 1px solid var(--glass-border); transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                                <td data-label="Title" style="padding: 15px;">
                                    <div style="font-weight: 600; color: var(--light); margin-bottom: 5px;">{{ $event->title }}</div>
                                    @if($event->description)
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ Str::limit($event->description, 60) }}</div>
                                    @endif
                                </td>
                                <td data-label="Venue" style="padding: 15px; color: var(--text-secondary);">
                                    {{ $event->venue ?? 'TBA' }}
                                    @if($event->location)
                                        <div style="font-size: 0.85rem; opacity: 0.8;">{{ $event->location }}</div>
                                    @endif
                                </td>
                                <td data-label="Date" style="padding: 15px; color: var(--text-secondary);">
                                    <div>{{ $event->event_date->format('M d, Y') }}</div>
                                    @if($event->event_date->format('H:i') !== '00:00')
                                        <div style="font-size: 0.85rem; opacity: 0.8;">{{ $event->event_date->format('g:i A') }}</div>
                                    @endif
                                </td>
                                <td data-label="Status" style="padding: 15px;">
                                    @php
                                        $statusColors = [
                                            'upcoming' => ['bg' => 'rgba(0, 204, 102, 0.2)', 'text' => 'var(--success)', 'label' => 'Upcoming'],
                                            'past' => ['bg' => 'rgba(255, 255, 255, 0.1)', 'text' => 'var(--text-secondary)', 'label' => 'Past'],
                                            'cancelled' => ['bg' => 'rgba(255, 0, 0, 0.2)', 'text' => 'var(--accent)', 'label' => 'Cancelled']
                                        ];
                                        $status = $statusColors[$event->status] ?? $statusColors['upcoming'];
                                    @endphp
                                    <span style="padding: 6px 12px; border-radius: 20px; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; font-size: 0.75rem; font-weight: 600;">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td data-label="Featured" style="padding: 15px;">
                                    @if($event->is_featured)
                                        <span style="color: var(--accent); font-size: 1.2rem;" title="Featured"><i class="fas fa-star"></i></span>
                                    @else
                                        <span style="color: var(--text-secondary); opacity: 0.3; font-size: 1.2rem;"><i class="far fa-star"></i></span>
                                    @endif
                                </td>
                                <td data-label="Actions" style="padding: 15px; text-align: right;">
                                    <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                                        <a href="{{ route('admin.events.edit', $event) }}" style="background: rgba(0, 204, 102, 0.2); color: var(--success); padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='rgba(0, 204, 102, 0.3)'" onmouseout="this.style.background='rgba(0, 204, 102, 0.2)'">
                                            <i class="fas fa-edit"></i> <span class="mobile-hide">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: rgba(255, 0, 0, 0.2); color: var(--accent); padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='rgba(255, 0, 0, 0.3)'" onmouseout="this.style.background='rgba(255, 0, 0, 0.2)'">
                                                <i class="fas fa-trash"></i> <span class="mobile-hide">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--glass-border);">
                {{ $events->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-calendar-times" style="font-size: 4rem; color: var(--text-secondary); opacity: 0.3; margin-bottom: 20px;"></i>
                <h3 style="color: var(--light); font-size: 1.3rem; margin-bottom: 10px; font-family: 'Orbitron', sans-serif;">No Events Found</h3>
                <p style="color: var(--text-secondary); margin-bottom: 25px;">Create your first event to get started.</p>
                <a href="{{ route('admin.events.create') }}" style="background: var(--accent); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            </div>
        @endif
    </div>
@endsection

