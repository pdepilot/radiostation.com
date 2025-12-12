@extends('layouts.frontend', ['title' => 'Feedback • Darling FM'])

@section('content')
    <div class="main-content" style="padding-top: 120px; min-height: calc(100vh - 200px);">
        <div class="container" style="max-width: 700px;">
            <h1 style="margin-bottom: 30px;">Share Your Feedback</h1>
            <p style="margin-bottom: 30px; color: var(--text-secondary);">We value your opinion! Your feedback helps us improve our services and provide you with the best radio experience possible.</p>
            
            <div class="contact-form-section">
                <div class="form-container">
                    @if(session('status'))
                        <div class="alert success" style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="feedback">
                        
                        <div class="input-group">
                            <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" placeholder=" " required>
                            <label for="name" class="form-label">Your Name</label>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder=" " required>
                            <label for="email" class="form-label">Your Email</label>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <input type="text" class="form-input" id="subject" name="subject" value="{{ old('subject', 'Feedback') }}" placeholder=" " required>
                            <label for="subject" class="form-label">Subject</label>
                            @error('subject')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="textarea-group">
                            <textarea class="form-textarea" id="message" name="message" placeholder=" " required>{{ old('message') }}</textarea>
                            <label for="message" class="form-label">Your Feedback</label>
                            @error('message')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> SUBMIT FEEDBACK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

