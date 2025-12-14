@extends('layouts.frontend', ['title' => 'Contact Us • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
    <style>
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr !important;
                gap: 30px !important;
            }
            
            .page-header h1 {
                font-size: 2rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <section class="container" style="padding-top: 120px;">
        <h2 class="section-title" style="text-align: center; margin-bottom: 50px;">CONTACT US</h2>

            <!-- Contact Grid -->
            <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 60px;">
                <!-- Contact Form -->
                <section class="contact-form-section">
                    <div class="form-container" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 40px; border: 1px solid var(--glass-border);">
                        <h2 class="form-title" style="font-family: 'Orbitron', sans-serif; color: var(--accent); font-size: 1.8rem; margin-bottom: 30px;">Send Us a Message</h2>
                        @if(session('status'))
                            <div class="alert success" style="background: rgba(0,255,0,0.1); color: #00ff00; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(0,255,0,0.3);">{{ session('status') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert error" style="background: rgba(255,0,0,0.1); color: #ff3333; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(255,0,0,0.3);">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                            @csrf
                            <div class="input-group" style="margin-bottom: 25px; position: relative;">
                                <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" placeholder=" " required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; transition: border-color 0.3s;">
                                <label for="name" class="form-label" style="position: absolute; left: 15px; top: 15px; color: var(--text-secondary); pointer-events: none; transition: all 0.3s; font-size: 1rem;">Your Name</label>
                            </div>
                            <div class="input-group" style="margin-bottom: 25px; position: relative;">
                                <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder=" " required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; transition: border-color 0.3s;">
                                <label for="email" class="form-label" style="position: absolute; left: 15px; top: 15px; color: var(--text-secondary); pointer-events: none; transition: all 0.3s; font-size: 1rem;">Email Address</label>
                            </div>
                            <div class="input-group" style="margin-bottom: 25px; position: relative;">
                                <input type="text" class="form-input" id="phone" name="phone" value="{{ old('phone') }}" placeholder=" " style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; transition: border-color 0.3s;">
                                <label for="phone" class="form-label" style="position: absolute; left: 15px; top: 15px; color: var(--text-secondary); pointer-events: none; transition: all 0.3s; font-size: 1rem;">Phone Number (Optional)</label>
                            </div>
                            <div class="input-group" style="margin-bottom: 25px; position: relative;">
                                <input type="text" class="form-input" id="subject" name="subject" value="{{ old('subject') }}" placeholder=" " required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; transition: border-color 0.3s;">
                                <label for="subject" class="form-label" style="position: absolute; left: 15px; top: 15px; color: var(--text-secondary); pointer-events: none; transition: all 0.3s; font-size: 1rem;">Subject</label>
                            </div>
                            <div class="input-group" style="margin-bottom: 25px; position: relative;">
                                <select class="form-input" id="type" name="type" required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; transition: border-color 0.3s; appearance: none; cursor: pointer;">
                                    <option value="">Select Category</option>
                                    <option value="general" @selected(old('type', $selectedCategory ?? '') === 'general')>General Inquiry</option>
                                    <option value="advertising" @selected(old('type', $selectedCategory ?? '') === 'advertising')>Advertising & Sponsorship</option>
                                    <option value="shoutout" @selected(old('type', $selectedCategory ?? '') === 'shoutout')>Shout-out Request (Anniversaries/Birthdays)</option>
                                    <option value="technical" @selected(old('type', $selectedCategory ?? '') === 'technical')>Technical Support</option>
                                    <option value="event_partnership" @selected(old('type', $selectedCategory ?? '') === 'event_partnership')>Event Partnership</option>
                                    <option value="feedback" @selected(old('type', $selectedCategory ?? '') === 'feedback')>Feedback</option>
                                </select>
                                <label for="type" class="form-label" style="position: absolute; left: 15px; top: -10px; background: var(--primary); padding: 0 5px; color: var(--text-secondary); font-size: 0.85rem;">Message Category</label>
                            </div>
                            <div class="textarea-group" style="margin-bottom: 25px; position: relative;">
                                <textarea class="form-textarea" id="message" name="message" placeholder=" " required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); outline: none; font-size: 1rem; min-height: 150px; resize: vertical; transition: border-color 0.3s;">{{ old('message') }}</textarea>
                                <label for="message" class="form-label" style="position: absolute; left: 15px; top: 15px; color: var(--text-secondary); pointer-events: none; transition: all 0.3s; font-size: 1rem;">Your Message</label>
                            </div>
                            <button type="submit" class="submit-btn" style="width: 100%; padding: 15px; background: var(--accent); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Contact Information -->
                <section class="contact-info-section">
                    <div class="info-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 30px; margin-bottom: 25px; border: 1px solid var(--glass-border); text-align: center; position: relative;">
                        <a href="https://maps.app.goo.gl/qPWKXDAngcD8thcc9" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit; display: inline-block; position: relative; z-index: 10;">
                            <div class="info-icon" style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255,0,0,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: var(--accent); cursor: pointer; transition: all 0.3s ease; border: 2px solid rgba(255,0,0,0.3); pointer-events: auto;" onmouseover="this.style.background='rgba(255,0,0,0.25)'; this.style.transform='scale(1.15)'; this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 20px rgba(255,0,0,0.4)'" onmouseout="this.style.background='rgba(255,0,0,0.15)'; this.style.transform='scale(1)'; this.style.borderColor='rgba(255,0,0,0.3)'; this.style.boxShadow='none'">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </a>
                        <h3 class="info-title" style="font-family: 'Orbitron', sans-serif; color: var(--accent); font-size: 1.3rem; margin-bottom: 15px;">Office Location</h3>
                        <div class="info-content" style="color: var(--light); line-height: 1.8;">
                            <p>Darling FM Broadcasting Center</p>
                            <p>Owerri, Imo State, Nigeria</p>
                        </div>
                    </div>

                    <div class="info-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 30px; margin-bottom: 25px; border: 1px solid var(--glass-border);">
                        <div class="info-icon" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,0,0,0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem; color: var(--accent);">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3 class="info-title" style="font-family: 'Orbitron', sans-serif; color: var(--accent); font-size: 1.3rem; margin-bottom: 15px;">Contact Information</h3>
                        <div class="info-content" style="color: var(--light); line-height: 1.8;">
                            <p><strong>Studio Hotline:</strong> <a href="tel:{{ str_replace(' ', '', $settings['studio_hotline'] ?? '+2348094441073') }}" style="color: var(--accent); text-decoration: none; transition: all 0.3s; display: inline-block; position: relative; z-index: 10; pointer-events: auto;" onmouseover="this.style.color='var(--light)'; this.style.textDecoration='underline'" onmouseout="this.style.color='var(--accent)'; this.style.textDecoration='none'">{{ $settings['studio_hotline'] ?? '+234 809 444 1073' }}</a></p>
                            <p><strong>WhatsApp:</strong> <a href="https://wa.me/{{ str_replace([' ', '+'], '', $settings['whatsapp_number'] ?? '+2348030001073') }}" target="_blank" rel="noopener noreferrer" style="color: var(--accent); text-decoration: none; transition: all 0.3s; display: inline-block; position: relative; z-index: 10; pointer-events: auto;" onmouseover="this.style.color='var(--light)'; this.style.textDecoration='underline'" onmouseout="this.style.color='var(--accent)'; this.style.textDecoration='none'">{{ $settings['whatsapp_number'] ?? '+234 803 000 1073' }}</a></p>
                            <p><strong>Available:</strong> 24/7</p>
                        </div>
                    </div>

                    <div class="info-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 30px; border: 1px solid var(--glass-border);">
                        <div class="info-icon" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,0,0,0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem; color: var(--accent);">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="info-title" style="font-family: 'Orbitron', sans-serif; color: var(--accent); font-size: 1.3rem; margin-bottom: 15px;">Email Addresses</h3>
                        <div class="info-content" style="color: var(--light); line-height: 1.8;">
                            <p><strong>General:</strong> <a href="mailto:info@darlingfm.ng" style="color: var(--accent); text-decoration: none; transition: all 0.3s; display: inline-block; position: relative; z-index: 10; pointer-events: auto;" onmouseover="this.style.color='var(--light)'; this.style.textDecoration='underline'" onmouseout="this.style.color='var(--accent)'; this.style.textDecoration='none'">info@darlingfm.ng</a></p>
                            <p><strong>Music:</strong> <a href="mailto:music@darlingfm.ng" style="color: var(--accent); text-decoration: none; transition: all 0.3s; display: inline-block; position: relative; z-index: 10; pointer-events: auto;" onmouseover="this.style.color='var(--light)'; this.style.textDecoration='underline'" onmouseout="this.style.color='var(--accent)'; this.style.textDecoration='none'">music@darlingfm.ng</a></p>
                            <p><strong>Partnerships:</strong> <a href="mailto:partners@darlingfm.ng" style="color: var(--accent); text-decoration: none; transition: all 0.3s; display: inline-block; position: relative; z-index: 10; pointer-events: auto;" onmouseover="this.style.color='var(--light)'; this.style.textDecoration='underline'" onmouseout="this.style.color='var(--accent)'; this.style.textDecoration='none'">partners@darlingfm.ng</a></p>
                        </div>
                    </div>
        </section>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/contact.js') }}" defer></script>
    <script>
        // Form label animation
        document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.transform = 'translateY(-25px) scale(0.85)';
                this.parentElement.querySelector('.form-label').style.color = 'var(--accent)';
                this.style.borderColor = 'var(--accent)';
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.querySelector('.form-label').style.transform = 'translateY(0) scale(1)';
                    this.parentElement.querySelector('.form-label').style.color = 'var(--text-secondary)';
                }
                this.style.borderColor = 'var(--glass-border)';
            });
            
            // Check if input has value on load
            if (input.value) {
                input.parentElement.querySelector('.form-label').style.transform = 'translateY(-25px) scale(0.85)';
            }
        });
        
        // Select label handling
        const typeSelect = document.getElementById('type');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                if (this.value) {
                    this.style.borderColor = 'var(--accent)';
                }
            });
        }
    </script>
@endpush
