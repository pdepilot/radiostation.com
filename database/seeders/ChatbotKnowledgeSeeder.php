<?php

namespace Database\Seeders;

use App\Models\ChatbotKnowledge;
use Illuminate\Database\Seeder;

class ChatbotKnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $knowledgeEntries = [
            // Advertising & Sponsorship
            [
                'keyword' => 'ad rates',
                'response' => "For ad rates and sponsorship packages, contact our sales team at info@darlingfm.ng or call +234 809 444 1073. We offer show sponsorships, spot ads, and program partnerships. Oya reach out, let's talk business! 💼",
                'question_patterns' => ['(ad|advert|advertisement).*rate', '(ad|advert|advertisement).*price', '(ad|advert|advertisement).*cost', 'sponsor.*package', 'how much.*ad'],
                'category' => 'advertising',
                'priority' => 10,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'sponsorship',
                'response' => "Want to sponsor a show or event? Sharp sharp contact us: info@darlingfm.ng or +234 809 444 1073. We have packages for show sponsorship, event partnerships, and brand collaborations. Let's make it happen! 🎯",
                'question_patterns' => ['sponsor.*show', 'sponsor.*event', 'sponsor.*program', 'become.*sponsor'],
                'category' => 'advertising',
                'priority' => 9,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Contact Information
            [
                'keyword' => 'contact',
                'response' => "Here's how to reach us:\n📞 Studio Hotline: +234 809 444 1073\n💬 WhatsApp: +234 803 000 1073\n📧 Email: info@darlingfm.ng\n📍 Location: Owerri, Imo State, Nigeria\n\nPick one and holler at us! 🎙️",
                'question_patterns' => ['how.*contact', 'contact.*info', 'phone.*number', 'studio.*line', 'hotline'],
                'category' => 'contact',
                'priority' => 10,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'whatsapp',
                'response' => "Our WhatsApp number is +234 803 000 1073. Reach out for music requests, shout-outs, feedback, or just to chat! We're here sharp sharp. 💬",
                'question_patterns' => ['whatsapp.*number', 'whats.*app', 'watsapp'],
                'category' => 'contact',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'email',
                'response' => "Send us an email at info@darlingfm.ng for business inquiries, press releases, partnerships, or general info. We'll respond sharp sharp! 📧",
                'question_patterns' => ['email.*address', 'e.?mail.*us', 'send.*email'],
                'category' => 'contact',
                'priority' => 7,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Listen Live & Streaming
            [
                'keyword' => 'listen live',
                'response' => "Listen live o! Tune in to 107.3 FM on your radio, or stream online at darlingfm.ng/live. You can also download our app on iOS/Android. Don't miss out! 🎧",
                'question_patterns' => ['listen.*live', 'live.*stream', 'tune.*in', 'listen.*online', 'live.*radio'],
                'category' => 'streaming',
                'priority' => 10,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'stream',
                'response' => "Stream Darling FM live at darlingfm.ng/live or download our mobile app. If you're having streaming issues, email us at info@darlingfm.ng with details. Let's fix that wahala! 🔧",
                'question_patterns' => ['stream.*live', 'online.*stream', 'web.*stream', 'stream.*issue'],
                'category' => 'streaming',
                'priority' => 9,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'app',
                'response' => "Download the Darling FM app from the App Store (iOS) or Google Play Store (Android). Listen on the go, get show schedules, and stay updated! 📱",
                'question_patterns' => ['mobile.*app', 'download.*app', 'ios.*app', 'android.*app', 'phone.*app'],
                'category' => 'streaming',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'frequency',
                'response' => "We broadcast on 107.3 FM in Owerri, Imo State, Nigeria. You can also listen worldwide online at darlingfm.ng/live. Tune in! 📻",
                'question_patterns' => ['what.*frequency', 'fm.*frequency', 'radio.*frequency', 'which.*station'],
                'category' => 'streaming',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Shows & Presenters
            [
                'keyword' => 'shows',
                'response' => "Check out our amazing shows! Visit darlingfm.ng/shows for full schedules, presenter info, and show times. We've got something for everyone - morning vibes, afternoon drive, evening tunes! 🎵",
                'question_patterns' => ['show.*schedule', 'what.*shows', 'program.*schedule', 'show.*time', 'when.*show'],
                'category' => 'shows',
                'priority' => 9,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'presenters',
                'response' => "Meet our talented presenters! Visit darlingfm.ng/shows to see all our on-air personalities, their schedules, and show details. They're all fire! 🔥",
                'question_patterns' => ['who.*presenter', 'presenter.*name', 'dj.*name', 'host.*name', 'on.*air.*personality'],
                'category' => 'shows',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'schedule',
                'response' => "Want to know what's playing? Visit darlingfm.ng/shows for our complete show schedule. From morning till night, we keep you entertained! 📅",
                'question_patterns' => ['program.*schedule', 'schedule.*today', 'what.*playing', 'current.*show'],
                'category' => 'shows',
                'priority' => 7,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Events & Roadshows
            [
                'keyword' => 'events',
                'response' => "Stay tuned for exciting events and roadshows! Check darlingfm.ng/events for upcoming happenings. Follow us on social media @darlingfm1073 so you don't miss out! 🎉",
                'question_patterns' => ['upcoming.*event', 'what.*event', 'event.*calendar', 'next.*event'],
                'category' => 'events',
                'priority' => 9,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'roadshow',
                'response' => "Roadshow coming soon o! Keep an eye on darlingfm.ng/events or follow @darlingfm1073 for updates. We'll let you know where we're heading next! 🚗💨",
                'question_patterns' => ['roadshow.*when', 'where.*roadshow', 'roadshow.*location', 'next.*roadshow'],
                'category' => 'events',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Music Requests & Shoutouts
            [
                'keyword' => 'music request',
                'response' => "Got a song you want to hear? WhatsApp us at +234 803 000 1073 or call +234 809 444 1073 during live shows. Drop the artist and song name, and we'll play it! 🎵",
                'question_patterns' => ['request.*song', 'play.*song', 'music.*request', 'song.*request', 'dedicate.*song'],
                'category' => 'requests',
                'priority' => 9,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'shoutout',
                'response' => "Want to give someone a shoutout? WhatsApp +234 803 000 1073 or call +234 809 444 1073 during shows. We'll make sure they hear it! Big up yourself! 📣",
                'question_patterns' => ['shout.*out', 'big.*up', 'greet.*someone', 'say.*hi.*to'],
                'category' => 'requests',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'dedication',
                'response' => "Send a dedication sharp sharp! WhatsApp +234 803 000 1073 with the song, artist, and your message. We'll play it during the show! 💝",
                'question_patterns' => ['dedicate.*to', 'song.*dedication', 'send.*dedication'],
                'category' => 'requests',
                'priority' => 7,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Contests & Competitions
            [
                'keyword' => 'contest',
                'response' => "Want to win something? Follow us on social media @darlingfm1073 for contest updates, or call +234 809 444 1073 during shows to participate. Good luck! 🎁",
                'question_patterns' => ['contest.*now', 'how.*join.*contest', 'current.*contest', 'win.*prize'],
                'category' => 'contests',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'competition',
                'response' => "Competitions are running! Check darlingfm.ng or follow @darlingfm1073 on social media for details. Call in during shows or WhatsApp +234 803 000 1073. May the best person win! 🏆",
                'question_patterns' => ['competition.*details', 'join.*competition', 'what.*competition'],
                'category' => 'contests',
                'priority' => 7,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Technical Issues
            [
                'keyword' => 'technical',
                'response' => "Having technical issues? Email us at info@darlingfm.ng with details of the problem (what's happening, device/browser, etc.). We'll sort it out sharp sharp! 🔧",
                'question_patterns' => ['technical.*issue', 'tech.*problem', 'stream.*not.*work', 'problem.*stream'],
                'category' => 'technical',
                'priority' => 8,
                'is_active' => true,
                'usage_count' => 0,
            ],
            [
                'keyword' => 'not playing',
                'response' => "Stream not playing? Try refreshing the page, clearing your browser cache, or using a different browser. If it still doesn't work, email info@darlingfm.ng with details. We'll fix it! 🛠️",
                'question_patterns' => ['stream.*not.*play', 'audio.*not.*work', 'can.*t.*listen', 'stream.*down'],
                'category' => 'technical',
                'priority' => 7,
                'is_active' => true,
                'usage_count' => 0,
            ],
            
            // Greetings
            [
                'keyword' => 'hello',
                'response' => "Hello! Welcome to Darling FM 107.3! 👋\n\nHow can I help you today? You can ask about: ad rates, shows, events, music requests, or just holler if you need anything!",
                'question_patterns' => ['^hi$', '^hey$', '^hello$', 'good.*morning', 'good.*afternoon', 'good.*evening'],
                'category' => 'greeting',
                'priority' => 6,
                'is_active' => true,
                'usage_count' => 0,
            ],
        ];

        foreach ($knowledgeEntries as $entry) {
            ChatbotKnowledge::updateOrCreate(
                ['keyword' => $entry['keyword']],
                $entry
            );
        }
    }
}

