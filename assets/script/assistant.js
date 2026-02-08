class SyraaAssistant {
    constructor() {
        this.events = {}; 
        this.isLoaded = false;
        this.initializeData();
        this.initializeUI();
        this.injectStyles(); // Inject custom styles for Welcome Screen
        this.bindEvents();
        this.conversationState = {
            mode: 'normal',
            context: null
        };
    }

    async initializeData() {
        try {
            const formData = new FormData();
            formData.append('get_all_events', '1');
            const response = await fetch('aiBackend.php', { method: 'POST', body: formData });
            const result = await response.json();
            if(result.status === 200) {
                this.events = result.data;
                this.isLoaded = true;
            }
        } catch(e) {
            console.error("Failed to load events", e);
            this.isLoaded = true; 
        }
    }

    initializeUI() {
        const assistant = document.createElement('div');
        assistant.className = 'ai-assistant';
        assistant.innerHTML = `
            <button class="ai-toggle">
                <i class="ri-sparkling-fill"></i>
            </button>
            <div class="ai-chat">
                <div class="ai-header">
                    <div class="ai-header-title">
                        <i class="ri-sparkling-2-line"></i>
                        <span>Syraa AI</span>
                    </div>
                    <button class="close-chat">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="ai-messages"></div>
                <!-- Hidden forms container for cloning -->
                <div id="ai-forms-template" style="display:none;"></div>
                <div class="ai-input">
                    <input type="text" placeholder="Ask Syraa AI...">
                    <button><i class="ri-send-plane-2-fill"></i></button>
                </div>
            </div>
        `;
        document.body.appendChild(assistant);
    }

    injectStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Syraa AI - Gemini Theme */
            .ai-assistant {
                position: fixed;
                bottom: 25px;
                right: 25px;
                z-index: 9999;
                font-family: 'Outfit', sans-serif;
            }

            .ai-toggle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: #1e1f20;
                border: 1px solid #333;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(0,0,0,0.4);
                color: white;
                font-size: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .ai-toggle::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                padding: 2px;
                background: linear-gradient(135deg, #4285f4, #d96570);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
            }

            .ai-toggle i {
                background: linear-gradient(135deg, #4285f4, #d96570);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .ai-toggle:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(66, 133, 244, 0.3);
            }

            .ai-chat {
                position: absolute;
                bottom: 80px;
                right: 0;
                width: 380px;
                height: 650px;
                max-height: 85vh;
                background: #1e1f20; /* Dark Grey Surface */
                border-radius: 24px;
                box-shadow: 0 12px 40px rgba(0,0,0,0.5);
                display: none;
                flex-direction: column;
                overflow: hidden;
                border: 1px solid rgba(255,255,255,0.08);
            }

            .ai-chat.active {
                display: flex;
            }

            .ai-header {
                padding: 18px 24px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #1e1f20;
                z-index: 10;
            }

            .ai-header-title {
                display: flex;
                align-items: center;
                gap: 12px;
                font-family: 'Space Grotesk', sans-serif;
                font-weight: 600;
                font-size: 1.1rem;
                color: #e3e3e3;
            }

            .ai-header-title i {
                font-size: 1.2rem;
                background: linear-gradient(135deg, #4285f4, #d96570);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .close-chat {
                background: none;
                border: none;
                color: #8e918f;
                cursor: pointer;
                font-size: 1.4rem;
                transition: color 0.2s;
                padding: 0;
            }

            .close-chat:hover {
                color: #e3e3e3;
            }

            .ai-messages {
                flex: 1;
                overflow-y: auto;
                padding: 0 24px 20px;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            .ai-messages::-webkit-scrollbar {
                width: 4px;
            }
            .ai-messages::-webkit-scrollbar-thumb {
                background: #444;
                border-radius: 2px;
            }

            /* Welcome Screen */
            .ai-welcome-screen {
                padding-top: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                animation: fadeIn 0.5s ease;
            }

            .ai-welcome-header {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 3rem;
                font-weight: 600;
                letter-spacing: -0.02em;
                line-height: 1.1;
                margin-bottom: 8px;
                background: linear-gradient(90deg, #4285F4, #9B72CB, #D96570);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                display: inline-block;
            }

            .ai-welcome-sub {
                font-family: 'Outfit', sans-serif;
                font-size: 1.8rem;
                font-weight: 500;
                color: #5d5e60; /* Darker grey */
                margin-bottom: 40px;
            }

            .suggestion-grid {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .suggestion-card {
                background: #2a2b2d;
                padding: 16px 20px;
                border-radius: 12px; /* Changed from 16px to 12px for more pill-like */
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border: 1px solid transparent;
            }

            .suggestion-card:hover {
                background: #3c4043;
                transform: translateY(-2px);
            }

            .suggestion-card span {
                font-size: 0.95rem;
                color: #e3e3e3;
                font-weight: 400;
            }

            .suggestion-card-icon {
                background: #3c4043;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .suggestion-card:hover .suggestion-card-icon {
                background: #4a4d51;
            }

            .suggestion-card-icon i {
                font-size: 1rem;
                color: #a8c7fa;
            }

            /* Chat Messages */
            .ai-message {
                display: flex;
                gap: 16px;
                font-size: 0.95rem;
                line-height: 1.6;
                color: #e3e3e3;
                animation: fadeIn 0.3s ease;
            }

            .ai-message.user {
                flex-direction: row-reverse;
                align-items: center;
            }

            .ai-message.assistant .message-avatar {
                width: 28px;
                height: 28px;
                flex-shrink: 0;
                display: flex;
                align-items: start;
                justify-content: center;
                margin-top: 4px; /* Align with text */
            }

            .ai-message.assistant .message-avatar i {
                font-size: 1.4rem;
                background: linear-gradient(135deg, #4285f4, #d96570);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .ai-message.user .message-avatar {
                display: none;
            }

            .message-content {
                max-width: 85%;
            }

            .ai-message.assistant .message-content {
                background: none;
                padding-top: 4px;
            }

            .ai-message.user .message-content {
                background: #37393b;
                padding: 12px 18px;
                border-radius: 20px 20px 4px 20px;
                color: #fff;
            }

            /* Input Area */
            .ai-input {
                padding: 24px;
                background: #1e1f20;
                display: flex;
                gap: 12px;
                align-items: center;
            }

            .ai-input input {
                flex: 1;
                background: #2a2b2d;
                border: 1px solid transparent;
                padding: 16px 24px;
                border-radius: 50px;
                color: white;
                font-family: inherit;
                font-size: 1rem;
                outline: none;
                transition: all 0.2s;
            }

            .ai-input input:focus {
                background: #2f3033;
                border-color: #5f6368;
            }

            .ai-input input::placeholder {
                color: #8e918f;
            }

            .ai-input button {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: white;
                color: #1e1f20;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                transition: opacity 0.2s;
                flex-shrink: 0;
            }

            .ai-input button:hover {
                opacity: 0.9;
            }
            
            /* Print Button in Pass */
            .print-btn {
                 cursor: pointer;
                 padding: 10px;
                 width: 100%;
                 margin-top: 10px;
                 background: #303134;
                 color: #a8c7fa;
                 border: none;
                 border-radius: 8px;
                 transition: background 0.2s;
                 font-weight: 500;
            }
            .print-btn:hover {
                background: #3c4043;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Typing Indicator */
            .typing-indicator {
                display: flex;
                gap: 16px;
                margin-bottom: 20px;
                animation: fadeIn 0.3s ease;
            }

            .typing-avatar {
                width: 28px;
                height: 28px;
                display: flex;
                align-items: start;
                justify-content: center;
                margin-top: 4px;
                flex-shrink: 0;
            }

            .typing-avatar i {
                font-size: 1.4rem;
                background: linear-gradient(135deg, #4285f4, #d96570);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .typing-dots {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .typing-dot {
                width: 6px;
                height: 6px; /* Small dots */
                background: #e3e3e3;
                border-radius: 50%;
                opacity: 0.7;
                animation: typingBounce 1.4s infinite ease-in-out both;
            }

            .typing-dot:nth-child(1) { animation-delay: -0.32s; }
            .typing-dot:nth-child(2) { animation-delay: -0.16s; }
            
            @keyframes typingBounce {
                0%, 80%, 100% { transform: scale(0); }
                40% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    }

    bindEvents() {
        const toggle = document.querySelector('.ai-toggle');
        const chat = document.querySelector('.ai-chat');
        const input = document.querySelector('.ai-input input');
        const sendBtn = document.querySelector('.ai-input button');
        const closeBtn = document.querySelector('.close-chat');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            chat.classList.toggle('active');
            if (chat.classList.contains('active')) {
                // If empty or previously closed, show welcome screen
                if(document.querySelector('.ai-messages').children.length === 0 || document.querySelector('.ai-messages').innerHTML === '') {
                    this.showWelcomeScreen();
                }
            } else {
                 setTimeout(() => {
                    this.resetChat();
                }, 300);
            }
        });

        const sendMessage = () => {
            const message = input.value.trim();
            if (message) {
                // If sending message from welcome screen, clear it first IF it's the welcome screen
                if(document.querySelector('.ai-welcome-screen')) {
                    document.querySelector('.ai-messages').innerHTML = '';
                }
                
                this.addMessage('user', message);
                this.processQuery(message);
                input.value = '';
            }
        };

        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            chat.classList.remove('active');
             setTimeout(() => {
                this.resetChat();
            }, 300);
        });

        // Prevent clicks inside chat from reaching toggle
        chat.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Event delegation
        document.querySelector('.ai-messages').addEventListener('click', (e) => {
            if(e.target.dataset.action === 'download-pass') {
                this.fetchPassDetails(e.target.dataset.code);
            }
            // Suggestion Card Click
            if(e.target.closest('.suggestion-card')) {
                const query = e.target.closest('.suggestion-card').dataset.query;
                
                // Transition effect: Clear welcome screen and send query
                document.querySelector('.ai-messages').innerHTML = '';
                
                this.addMessage('user', query);
                this.processQuery(query);
            }
        });
    }

    resetChat() {
        document.querySelector('.ai-messages').innerHTML = '';
        this.conversationState = { mode: 'normal', context: null };
    }

    showWelcomeScreen() {
        const messages = document.querySelector('.ai-messages');
        messages.innerHTML = `
            <div class="ai-welcome-screen">
                <div>
                    <div class="ai-welcome-header">Hello, There</div>
                    <div class="ai-welcome-sub">How can I help you today?</div>
                </div>
                <div class="suggestion-grid">
                    <div class="suggestion-card" data-query="List all events">
                        <span>Show me all events</span>
                        <div class="suggestion-card-icon"><i class="ri-calendar-event-line"></i></div>
                    </div>
                    <!-- How to Register Card Removed or Updated -->
                    <div class="suggestion-card" data-query="How to register?">
                        <span>How to register?</span>
                        <div class="suggestion-card-icon"><i class="ri-user-add-line"></i></div>
                    </div>
                    <div class="suggestion-card" data-query="Download my pass">
                        <span>Download Event Pass</span>
                        <div class="suggestion-card-icon"><i class="ri-ticket-2-line"></i></div>
                    </div>
                    <div class="suggestion-card" data-query="Rules and regulations">
                        <span>Rules & Regulations</span>
                        <div class="suggestion-card-icon"><i class="ri-file-list-3-line"></i></div>
                    </div>
                </div>
            </div>
        `;
    }

    addMessage(type, text, isHtml = false) {
        const messages = document.querySelector('.ai-messages');
        
        // Remove welcome screen if it exists and we are adding a message
        if(messages.querySelector('.ai-welcome-screen')) {
            messages.innerHTML = '';
        }

        const message = document.createElement('div');
        message.className = `ai-message ${type}`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = type === 'assistant' ? 
            '<i class="ri-sparkling-fill"></i>' : 
            '<i class="ri-user-smile-line"></i>';
        
        const content = document.createElement('div');
        content.className = 'message-content';
        
        if(isHtml) {
            content.innerHTML = text;
        } else {
            content.textContent = text;
        }
        
        message.appendChild(avatar);
        message.appendChild(content);
        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;
    }

    showTypingIndicator() {
        const messages = document.querySelector('.ai-messages');
        
        // Don't clear welcome screen for typing indicator, but maybe push it up? 
        // Better to clear welcome screen if user typed something.
        if(messages.querySelector('.ai-welcome-screen')) {
            messages.innerHTML = '';
        }

        const typing = document.createElement('div');
        typing.className = 'typing-indicator active';
        typing.innerHTML = `
            <div class="typing-avatar"><i class="ri-sparkling-fill"></i></div>
            <div class="typing-dots" style="display:flex; gap:4px; align-items:center;">
                <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>
            </div>`;
        messages.appendChild(typing);
        messages.scrollTop = messages.scrollHeight;
    }

    hideTypingIndicator() {
        const typing = document.querySelector('.typing-indicator.active');
        if (typing) typing.remove();
    }

    async processQuery(query) {
        this.showTypingIndicator();
        await new Promise(r => setTimeout(r, 600)); 
        this.hideTypingIndicator();

        const lowerQuery = query.toLowerCase();

        // 1. Download Pass Logic
        if(lowerQuery.includes('download') && (lowerQuery.includes('pass') || lowerQuery.includes('ticket'))) {
            this.addMessage('assistant', 'Sure! Please enter your Event Pass Code (e.g., ORA01AS0001) or Register Number to download your pass.');
            this.conversationState = { mode: 'awaiting_pass_code' };
            return;
        }

        if(this.conversationState.mode === 'awaiting_pass_code') {
            this.fetchPassDetails(query.trim());
            this.conversationState = { mode: 'normal' };
            return;
        }

        // 2. Registration Logic - REMOVED
        if(lowerQuery.includes('register')) {
             this.addMessage('assistant', 'Please visit our <a href="index.php#events" style="color:var(--secondary-color); text-decoration:underline;">Events Page</a> to register for events. I can help you with event details or downloading passes!', true);
             return;
        }

        // 3. Event Information Logic
        const foundEvent = Object.values(this.events).find(e => lowerQuery.includes(e.name.toLowerCase()));
        if(foundEvent) {
             let response = `<b>${foundEvent.name}</b><br>` +
                           `📅 Day: ${foundEvent.day}<br>` +
                           `📍 Venue: ${foundEvent.venue}<br>` +
                           `⏰ Time: ${foundEvent.time}<br>` +
                           `📜 Rules: ${foundEvent.rules || 'Check website for details'}<br><br>` +
                           `Please visit the website to register.`;
             this.addMessage('assistant', response, true);
             return;
        }

        // 4. List Events Logic
        if (lowerQuery.includes('list') || lowerQuery.includes('events')) {
            let eventsList = "Here are the events:<br><br><b>Day 1</b>:<br>";
            Object.values(this.events).filter(e => e.day === 'day1').forEach(e => eventsList += `• ${e.name}<br>`);
            
            eventsList += "<br><b>Day 2</b>:<br>";
            Object.values(this.events).filter(e => e.day === 'day2').forEach(e => eventsList += `• ${e.name}<br>`);
            
            this.addMessage('assistant', eventsList, true);
            return;
        }

        // Rules handling
        if (lowerQuery.includes('rule')) {
            this.addMessage('assistant', 'Please select an event to view its rules, or check the specific event card on the website.', true);
            return;
        }

        // 5. General / Fallback
        this.addMessage('assistant', "I'm not sure about that. I can help with Event Details, information about registration, or Downloading Passes. Try asking 'List all events' or 'How to register'.");
    }

    async fetchPassDetails(input) {
        input = input.trim();
        const formData = new FormData();
        
        if(input.toUpperCase().startsWith('ORA')) {
            formData.append('get_pass_by_code', '1');
            formData.append('pass_code', input);
        } else {
            formData.append('get_passes_by_regno', '1');
            formData.append('regno', input);
        }
        
        try {
            const response = await fetch('aiBackend.php', { method: 'POST', body: formData });
            const result = await response.json();

            if(result.status === 200) {
                const data = Array.isArray(result.data) ? result.data : [result.data];
                this.generatePassCard(data);
            } else {
                this.addMessage('assistant', `❌ ${result.message || 'Details not found.'} Please check your input.`);
            }
        } catch(e) {
            this.addMessage('assistant', 'Error fetching details.');
        }
    }

    generatePassCard(dataList) {
        let containerHtml = `<div style="display:flex; overflow-x:auto; gap:15px; padding:10px 5px; scroll-behavior:smooth;">`;

        dataList.forEach(data => {
            containerHtml += `
            <div id="pass-${data.pass_code}" style="flex:0 0 280px; background: #2a2b2d; border: 1px solid #444; color:#e3e3e3; padding:20px; border-radius:16px; text-align:center; position:relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                <div style="font-size:1.2em; font-weight:bold; border-bottom:1px solid #444; padding-bottom:8px; margin-bottom:12px; color: #fff;">Orlia '26 Pass</div>
                <div style="font-size:1.1em; font-weight:bold; margin-bottom:5px; height:40px; display:flex; align-items:center; justify-content:center; color:#a8c7fa;">${data.event}</div>
                <div style="font-size:0.9em; opacity:0.9;">${data.name}</div>
                <div style="font-size:1.1em; font-weight:bold; margin:10px 0; background:#1e1f20; padding:8px; border-radius:8px; border:1px solid #444; letter-spacing:1px;">${data.pass_code}</div>
                <div style="font-size:0.85em; margin-bottom:5px; color:#c4c7c5;">📅 ${data.day} | ⏰ ${data.time}</div>
                <div style="font-size:0.85em; color:#c4c7c5;">📍 ${data.venue}</div>
                
                <button onclick="
                    const w = window.open('', '_blank');
                    w.document.write('<html><body style=\\'display:flex;justify-content:center;align-items:center;height:100vh;background:#f0f0f0;font-family:sans-serif;\\'>' + document.getElementById('pass-${data.pass_code}').cloneNode(true).outerHTML + '</body></html>');
                    w.document.close();
                    w.print();
                " class="print-btn">Print / Save</button>
            </div>`;
        });
        
        containerHtml += `</div>`;
        this.addMessage('assistant', "Here are your passes:", false);
        this.addMessage('assistant', containerHtml, true);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SyraaAssistant();
});
