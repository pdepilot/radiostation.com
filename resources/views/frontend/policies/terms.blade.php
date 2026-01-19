@extends('layouts.frontend', ['title' => 'Terms of Service • Darling FM'])

@section('content')
    <div class="main-content" style="padding-top: 120px; min-height: calc(100vh - 200px);">
        <div class="container" style="max-width: 900px;">
            <h1 class="section-title">TERMS OF SERVICE</h1>
            <div style="line-height: 1.8; color: var(--text-secondary);">
                <p style="margin-bottom: 20px;"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">1. Acceptance of Terms</h2>
                <p style="margin-bottom: 15px;">By accessing and using Darling FM's website and services, you accept and agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">2. Use of Service</h2>
                <p style="margin-bottom: 15px;">You agree to use our service only for lawful purposes and in accordance with these Terms. You agree not to:</p>
                <ul style="margin-left: 30px; margin-bottom: 20px;">
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe upon the rights of others</li>
                    <li>Transmit any harmful or malicious code</li>
                    <li>Interfere with or disrupt the service</li>
                </ul>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">3. Content</h2>
                <p style="margin-bottom: 15px;">All content on Darling FM, including text, graphics, logos, and audio, is the property of Darling FM and protected by copyright laws. You may not reproduce, distribute, or create derivative works without our permission.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">4. User Accounts</h2>
                <p style="margin-bottom: 15px;">You are responsible for maintaining the confidentiality of your account credentials. You agree to notify us immediately of any unauthorized use of your account.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">5. Limitation of Liability</h2>
                <p style="margin-bottom: 15px;">Darling FM shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our service.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">6. Changes to Terms</h2>
                <p style="margin-bottom: 15px;">We reserve the right to modify these Terms at any time. Your continued use of the service constitutes acceptance of any changes.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">7. Contact Us</h2>
                <p style="margin-bottom: 15px;">For questions about these Terms, please contact us at <a href="/contact" style="color: var(--accent);">contact@darlingfm.ng</a>.</p>
            </div>
        </div>
    </div>
@endsection

