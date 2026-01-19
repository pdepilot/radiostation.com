@extends('layouts.frontend', ['title' => 'Privacy Policy • Darling FM'])

@section('content')
    <div class="main-content" style="padding-top: 120px; min-height: calc(100vh - 200px);">
        <div class="container" style="max-width: 900px;">
            <h1 class="section-title">PRIVACY POLICY</h1>
            <div style="line-height: 1.8; color: var(--text-secondary);">
                <p style="margin-bottom: 20px;"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">1. Information We Collect</h2>
                <p style="margin-bottom: 15px;">We collect information that you provide directly to us, including when you register for an account, subscribe to our newsletter, or contact us. This may include your name, email address, phone number, and any other information you choose to provide.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">2. How We Use Your Information</h2>
                <p style="margin-bottom: 15px;">We use the information we collect to:</p>
                <ul style="margin-left: 30px; margin-bottom: 20px;">
                    <li>Provide, maintain, and improve our services</li>
                    <li>Send you updates, newsletters, and promotional materials</li>
                    <li>Respond to your comments, questions, and requests</li>
                    <li>Monitor and analyze trends, usage, and activities</li>
                </ul>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">3. Information Sharing</h2>
                <p style="margin-bottom: 15px;">We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                <ul style="margin-left: 30px; margin-bottom: 20px;">
                    <li>With your consent</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect our rights and safety</li>
                </ul>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">4. Data Security</h2>
                <p style="margin-bottom: 15px;">We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet is 100% secure.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">5. Your Rights</h2>
                <p style="margin-bottom: 15px;">You have the right to access, update, or delete your personal information at any time. You can also opt-out of receiving promotional communications from us.</p>
                
                <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--highlight);">6. Contact Us</h2>
                <p style="margin-bottom: 15px;">If you have any questions about this Privacy Policy, please contact us at <a href="/contact" style="color: var(--accent);">contact@darlingfm.ng</a>.</p>
            </div>
        </div>
    </div>
@endsection

