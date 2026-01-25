@extends('layouts.frontend', ['title' => 'FAQ • Darling FM'])

@section('content')
    <div class="main-content" style="padding-top: 120px; min-height: calc(100vh - 200px);">
        <div class="container" style="max-width: 900px;">
            <h1 class="section-title">FREQUENTLY ASKED QUESTIONS</h1>
            <div style="line-height: 1.8; color: var(--text-secondary);">
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How do I listen to Darling FM live?</h2>
                    <p>You can listen to our live stream by visiting the "Live Stream" page on our website. Simply click the play button to start listening. You can also access our stream through our mobile app.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How can I request a song?</h2>
                    <p>You can request songs through our contact form. Include the song title, artist name, and any special message you'd like to share. Our DJs will do their best to play your request during their shows.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How do I submit a shoutout request?</h2>
                    <p>To request a shoutout, use our contact form and select "Shoutout Request" as the message category. Provide the name of the person you want to shout out and any special message.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">Can I advertise on Darling FM?</h2>
                    <p>Yes! We offer various advertising opportunities. Please contact us through our contact form and select "Advertising" as the message category. Our team will get back to you with available packages and rates.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How do I become a member?</h2>
                    <p>You can create an account by clicking the "Register" button in the navigation menu. Registration is free and gives you access to exclusive features like commenting on posts and ad-free browsing.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">Where can I find the show schedule?</h2>
                    <p>You can view our complete show schedule on the "Shows" page. The schedule includes show times, hosts, and descriptions for all our programs.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How can I contact a specific DJ or host?</h2>
                    <p>Visit the "DJs" page to view all our on-air personalities. Each profile includes contact information and social media links where you can reach out to them.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">Is there a mobile app?</h2>
                    <p>Yes, we have a mobile app available for download on iOS and Android devices. Check our website for download links and instructions.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">How do I report a technical issue?</h2>
                    <p>If you experience any technical problems, please contact us through our contact form and select "Technical" as the message category. Provide as much detail as possible about the issue you're experiencing.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 10px; color: var(--highlight);">Still have questions?</h2>
                    <p>If you can't find the answer you're looking for, please <a href="{{ route('contact.index') }}" wire:navigate style="color: var(--accent);">contact us</a> and we'll be happy to help!</p>
                </div>
            </div>
        </div>
    </div>
@endsection

