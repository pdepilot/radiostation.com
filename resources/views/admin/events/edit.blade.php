@extends('layouts.admin', ['title' => 'Edit Event'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <a href="{{ route('admin.events.index') }}" style="background: var(--glass); color: var(--light); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid var(--glass-border); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.borderColor='var(--glass-border)'; this.style.background='var(--glass)'">
            <i class="fas fa-arrow-left"></i> Back to Events
        </a>
    </div>

    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 30px; border: 1px solid var(--glass-border); max-width: 900px; margin: 0 auto;">
        <h2 style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 25px; font-size: 1.8rem;">Edit Event</h2>
        
        @if($errors->any())
            <div style="background: rgba(255, 0, 0, 0.2); border: 1px solid var(--accent); color: var(--accent); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-admin.event-form :event="$event"/>
        </form>
    </div>
@endsection

