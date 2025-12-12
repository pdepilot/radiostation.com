// AskDarling - Official Intelligent Chatbot for Darling FM 107.3
// Nigeria's premier edu-attainment, healthy Christian lifestyle, and contemporary talk-music radio station

document.addEventListener('DOMContentLoaded', function() {
    const chatbotBtn = document.getElementById('chatbotBtn');
    const chatbotModal = document.getElementById('chatbotModal');
    const closeChatbot = document.getElementById('closeChatbot');
    const chatbotSend = document.getElementById('chatbotSend');
    const chatbotInput = document.getElementById('chatbotInput');
    const chatbotMessages = document.getElementById('chatbotMessages');
    
    if (!chatbotBtn || !chatbotModal || !chatbotMessages) return;
    
    // Station Information
    const STATION_INFO = {
        frequency: '107.3 FM',
        location: 'Owerri, Imo State, Nigeria',
        website: 'darlingfm.ng',
        app: 'Darling FM app (iOS/Android)',
        liveStream: 'https://darlingfm.ng/live',
        studioHotline: '+234 809 444 1073',
        whatsapp: '+234 803 000 1073',
        email: 'info@darlingfm.ng',
        social: '@darlingfm1073'
    };
    
    // Knowledge Base
    const KNOWLEDGE_BASE = {
        // Ad rates & sponsorship
        'ad rate': 'For ad rates and sponsorship packages, please contact our sales team at ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '. We offer various packages including show sponsorships, spot ads, and program partnerships.',
        'ads rate': 'For ad rates and sponsorship packages, please contact our sales team at ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '. We offer various packages including show sponsorships, spot ads, and program partnerships.',
        'ad rates': 'For ad rates and sponsorship packages, please contact our sales team at ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '. We offer various packages including show sponsorships, spot ads, and program partnerships.',
        'sponsor': 'For sponsorship opportunities, contact us at ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '. We have packages for show sponsorship, event partnerships, and brand collaborations.',
        'advertisement': 'For advertising rates and packages, reach out to ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '.',
        
        // Booking presenters/DJs
        'book presenter': 'To book a presenter or DJ for your event, contact ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '. Please provide event details, date, and type of service needed.',
        'book dj': 'For DJ bookings, contact us at ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '. Include event date, venue, and requirements.',
        'hire presenter': 'To hire a presenter, email ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + ' with your event details.',
        
        // Business/PR/Collaboration
        'business': 'For business inquiries, partnerships, or collaborations, contact ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '.',
        'partnership': 'For partnership opportunities, reach out to ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '.',
        'collaboration': 'For collaboration inquiries, contact ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '.',
        'pr': 'For PR inquiries, contact ' + STATION_INFO.email + ' or ' + STATION_INFO.studioHotline + '.',
        
        // Press releases
        'press release': 'To send press releases or media invites, email ' + STATION_INFO.email + ' with subject "Press Release" or "Media Invite".',
        'media invite': 'Send media invites to ' + STATION_INFO.email + ' with event details, date, and venue.',
        
        // Jobs & internships
        'job': 'For job vacancies and career opportunities, check our website ' + STATION_INFO.website + ' or send your CV to ' + STATION_INFO.email + '.',
        'vacancy': 'For current job openings, visit ' + STATION_INFO.website + ' or email ' + STATION_INFO.email + '.',
        'internship': 'For internship applications, send your application to ' + STATION_INFO.email + ' with subject "Internship Application".',
        'career': 'For career opportunities, visit ' + STATION_INFO.website + ' or contact ' + STATION_INFO.email + '.',
        
        // Office & contacts
        'address': 'Darling FM is located in Owerri, Imo State, Nigeria. For exact office address, contact ' + STATION_INFO.studioHotline + ' or ' + STATION_INFO.email + '.',
        'office': 'For office location and directions, call ' + STATION_INFO.studioHotline + ' or email ' + STATION_INFO.email + '.',
        'contact': 'Contact us: Studio Hotline ' + STATION_INFO.studioHotline + ', WhatsApp ' + STATION_INFO.whatsapp + ', Email ' + STATION_INFO.email + '.',
        
        // Live stream & listening
        'listen': 'Listen live at ' + STATION_INFO.liveStream + ' or download the Darling FM app on iOS/Android. You can also tune in to ' + STATION_INFO.frequency + ' on your radio.',
        'stream': 'Stream live at ' + STATION_INFO.liveStream + ' or use the Darling FM app. For stream issues, contact ' + STATION_INFO.email + '.',
        'app': 'Download the Darling FM app from the App Store (iOS) or Google Play Store (Android).',
        'online': 'Listen online at ' + STATION_INFO.liveStream + ' or use our mobile app.',
        
        // Music & playlist
        'song': 'For song information or playlist requests, contact us via WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + ' during live shows.',
        'playlist': 'For playlist information, contact us via WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + '.',
        'music': 'For music requests or song information, WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + ' during shows.',
        
        // Presenters & shows
        'presenter': 'For presenter profiles and show schedules, visit ' + STATION_INFO.website + '/shows or check our website\'s "Shows" section.',
        'show': 'For show times and details, visit ' + STATION_INFO.website + '/shows. You can also call ' + STATION_INFO.studioHotline + ' for information.',
        'dj': 'For DJ profiles and schedules, visit ' + STATION_INFO.website + '/shows or contact ' + STATION_INFO.studioHotline + '.',
        
        // Events
        'event': 'For upcoming events and roadshows, visit ' + STATION_INFO.website + '/events or follow us on social media @' + STATION_INFO.social + '.',
        'roadshow': 'For roadshow information and registration, visit ' + STATION_INFO.website + '/events or contact ' + STATION_INFO.studioHotline + '.',
        
        // Contests
        'contest': 'For current contests and how to join, follow us on social media @' + STATION_INFO.social + ' or call ' + STATION_INFO.studioHotline + ' during shows.',
        'competition': 'For competition details, visit ' + STATION_INFO.website + ' or follow @' + STATION_INFO.social + ' on social media.',
        'winner': 'For past contest winners, check our website ' + STATION_INFO.website + ' or social media @' + STATION_INFO.social + '.',
        
        // Traffic & weather
        'traffic': 'For Imo State traffic updates, tune in during our traffic reports or follow @' + STATION_INFO.social + ' for updates.',
        'weather': 'For weather updates, tune in during our weather segments or follow @' + STATION_INFO.social + '.',
        'news': 'For breaking news, tune in to ' + STATION_INFO.frequency + ' or visit ' + STATION_INFO.website + '/news.',
        
        // Technical issues
        'technical': 'For technical issues with streaming or the app, email ' + STATION_INFO.email + ' with details of the problem.',
        'not playing': 'If the stream is not playing, try refreshing the page or contact ' + STATION_INFO.email + ' for support.',
        'problem': 'For technical problems, email ' + STATION_INFO.email + ' with a description of the issue.',
        
        // Shout-outs & requests
        'shout': 'For shout-outs, WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + ' during live shows.',
        'request': 'For music requests, WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + ' during shows.',
        'message': 'To send a message to presenters, WhatsApp ' + STATION_INFO.whatsapp + ' or call ' + STATION_INFO.studioHotline + '.',
    };
    
    // Greeting message
    const GREETING = "Hi! Welcome to Darling FM 107.3. How can I help you today – ads, events, music request, or something else?";
    
    // Chat history storage key
    const CHAT_HISTORY_KEY = 'askdarling_chat_history';
    
    // Load chat history
    function loadChatHistory() {
        try {
            const savedHistory = localStorage.getItem(CHAT_HISTORY_KEY);
            if (savedHistory) {
                const history = JSON.parse(savedHistory);
                // Clear current messages
                chatbotMessages.innerHTML = '';
                // Restore messages
                history.forEach(msg => {
                    if (msg.type === 'user') {
                        addUserMessage(msg.text, false);
                    } else {
                        addBotMessage(msg.text, false);
                    }
                });
            } else {
                // No history, show greeting
                addBotMessage(GREETING, false);
            }
        } catch (e) {
            console.error('Error loading chat history:', e);
            addBotMessage(GREETING, false);
        }
    }
    
    // Save chat history
    function saveChatHistory() {
        try {
            const messages = [];
            chatbotMessages.querySelectorAll('div').forEach(msgDiv => {
                const text = msgDiv.textContent.replace(/^(You|AskDarling):\s*/, '');
                const type = msgDiv.textContent.startsWith('You:') ? 'user' : 'bot';
                messages.push({ type, text });
            });
            // Keep last 50 messages to avoid storage issues
            const recentMessages = messages.slice(-50);
            localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(recentMessages));
        } catch (e) {
            console.error('Error saving chat history:', e);
        }
    }
    
    // Initialize with greeting or history
    loadChatHistory();
    
    // Toggle chatbot
    if (chatbotBtn) {
        chatbotBtn.addEventListener('click', function() {
            chatbotModal.style.display = chatbotModal.style.display === 'flex' ? 'none' : 'flex';
            if (chatbotModal.style.display === 'flex') {
                chatbotInput.focus();
            }
        });
    }
    
    // Close chatbot
    if (closeChatbot) {
        closeChatbot.addEventListener('click', function() {
            chatbotModal.style.display = 'none';
        });
    }
    
    // Send message
    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;
        
        // Add user message
        addUserMessage(message);
        chatbotInput.value = '';
        
        // Process and respond
        setTimeout(() => {
            const response = getResponse(message);
            addBotMessage(response);
        }, 500);
    }
    
    if (chatbotSend && chatbotInput) {
        chatbotSend.addEventListener('click', sendMessage);
        chatbotInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    // Get response based on user input
    function getResponse(userMessage) {
        const lowerMessage = userMessage.toLowerCase();
        
        // Check if off-topic
        const offTopicKeywords = ['weather', 'sports', 'politics', 'cooking', 'recipe', 'movie', 'game'];
        const isOffTopic = offTopicKeywords.some(keyword => lowerMessage.includes(keyword) && 
            !lowerMessage.includes('traffic') && !lowerMessage.includes('news'));
        
        if (isOffTopic && !lowerMessage.includes('darling') && !lowerMessage.includes('radio')) {
            return "I'm here only for Darling FM matters. How can I assist with the station?";
        }
        
        // Check knowledge base
        for (const [key, response] of Object.entries(KNOWLEDGE_BASE)) {
            if (lowerMessage.includes(key)) {
                return response;
            }
        }
        
        // Check for specific patterns
        if (lowerMessage.includes('hello') || lowerMessage.includes('hi') || lowerMessage.includes('hey')) {
            return GREETING;
        }
        
        if (lowerMessage.includes('frequency') || lowerMessage.includes('fm')) {
            return 'Darling FM broadcasts on ' + STATION_INFO.frequency + ' in ' + STATION_INFO.location + '. We also stream worldwide at ' + STATION_INFO.liveStream + '.';
        }
        
        if (lowerMessage.includes('location') || lowerMessage.includes('where')) {
            return 'Darling FM is located in ' + STATION_INFO.location + '. For exact address, contact ' + STATION_INFO.studioHotline + '.';
        }
        
        if (lowerMessage.includes('website') || lowerMessage.includes('site')) {
            return 'Visit our website at ' + STATION_INFO.website + ' for shows, news, events, and more.';
        }
        
        if (lowerMessage.includes('phone') || lowerMessage.includes('call') || lowerMessage.includes('number')) {
            return 'Studio Hotline: ' + STATION_INFO.studioHotline + ' | WhatsApp: ' + STATION_INFO.whatsapp + ' | Email: ' + STATION_INFO.email;
        }
        
        if (lowerMessage.includes('social') || lowerMessage.includes('instagram') || lowerMessage.includes('twitter') || lowerMessage.includes('facebook')) {
            return 'Follow us on all platforms @' + STATION_INFO.social + ' for updates, contests, and more.';
        }
        
        if (lowerMessage.includes('email')) {
            return 'Email us at ' + STATION_INFO.email + ' for inquiries, press releases, or general information.';
        }
        
        // Check for ad rates variations (must be before default)
        if (lowerMessage.includes('ad') && (lowerMessage.includes('rate') || lowerMessage.includes('price') || lowerMessage.includes('cost') || lowerMessage.includes('fee'))) {
            return 'For ad rates and sponsorship packages, please contact our sales team at ' + STATION_INFO.email + ' or call ' + STATION_INFO.studioHotline + '. We offer various packages including show sponsorships, spot ads, and program partnerships.';
        }
        
        // Default response
        return "I can help with ad rates, events, music requests, show schedules, contests, technical issues, and more. What specifically do you need? You can also contact us directly: " + STATION_INFO.studioHotline + " or " + STATION_INFO.email;
    }
    
    // Add user message
    function addUserMessage(message, save = true) {
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'background: rgba(255,0,0,0.1); padding: 12px 15px; border-radius: 10px; color: var(--light); align-self: flex-end; max-width: 80%; margin-left: auto;';
        messageDiv.innerHTML = '<strong>You:</strong> ' + escapeHtml(message);
        chatbotMessages.appendChild(messageDiv);
        scrollToBottom();
        if (save) {
            saveChatHistory();
        }
    }
    
    // Add bot message
    function addBotMessage(message, save = true) {
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'background: rgba(255,0,0,0.1); padding: 12px 15px; border-radius: 10px; color: var(--light); align-self: flex-start; max-width: 80%;';
        messageDiv.innerHTML = '<strong>AskDarling:</strong> ' + escapeHtml(message);
        chatbotMessages.appendChild(messageDiv);
        scrollToBottom();
        if (save) {
            saveChatHistory();
        }
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Close on outside click
    document.addEventListener('click', function(e) {
        if (chatbotModal && chatbotModal.style.display === 'flex' && 
            !chatbotModal.contains(e.target) && 
            !chatbotBtn.contains(e.target)) {
            chatbotModal.style.display = 'none';
        }
    });
});

