<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div id="chat-room" class="fixed bottom-4 right-4 w-96 h-96 
                bg-white rounded-lg shadow-lg flex flex-col overflow-hidden font-sans text-sm transition-all duration-300">

                <!-- Header -->
                <div id="chat-room-header" class="bg-gray-800 text-white p-3 flex justify-between items-center cursor-pointer">
                    <span>Chat</span>
                    <button id="chat-minimize" class="text-xl leading-none hover:text-gray-300">&#9650;</button>
                </div>

                <!-- Messages -->
                <div id="messages" class="flex-1 p-3 overflow-y-auto space-y-2 bg-gray-50"></div>

                <!-- Input -->
                <!-- Input + chat type selector -->
                <!-- Input + chat type toggle -->
                <div id="chat-input-container" class="border-t p-2 bg-white flex flex-col space-y-2">

                    @if(auth()->user()?->gang_id)
                        <!-- Slide toggle -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">All</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="chat-type-toggle" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-red-500 transition"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full 
                                            transform peer-checked:translate-x-6 transition"></div>
                            </label>
                            <span class="text-sm">Gang</span>
                        </div>
                    @endif

                    <!-- Input + button -->
                    <div class="flex">
                        <input id="chat-input" type="text" placeholder="Typ je bericht..." 
                            class="flex-1 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-red-300" />
                        <button id="chat-send" class="ml-2 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Verstuur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes blink {
        0%, 100% { background-color: #1f2937; } /* gray-800 */
        50% { background-color: #b91c1c; }      /* red-700 */
    }
    
    .chat-blink {
        animation: blink 1s infinite;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatRoom = document.getElementById('chat-room');
    const messagesDiv = document.getElementById('messages');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send');
    const inputContainer = document.getElementById('chat-input-container');
    const minimizeBtn = document.getElementById('chat-minimize');
    const header = document.getElementById('chat-room-header');
    let lastMessageCount = 0;
    let unreadCount = 0;
    let initialized = false;
    const headerTitle = header.querySelector('span');


    // Simple cookie helpers
    function setCookie(name, value, days = 365) {
        const d = new Date();
        d.setTime(d.getTime() + (days*24*60*60*1000));
        document.cookie = `${name}=${value};path=/;expires=${d.toUTCString()}`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function openChat() {
        minimized = false;
        setCookie('chat_minimized', 'false');

        // stop eventuele notificatie-states later
        const header = document.getElementById('chat-room-header');
        header.classList.remove('animate-pulse');

        updateChatState();

        // netjes naar beneden scrollen
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    header.addEventListener('click', (e) => {
        // voorkom dubbele toggle als je op de knop klikt
        if (e.target === minimizeBtn) return;

        if (minimized) {
            openChat();
        }
    });

    // Init state van cookie
    let minimized = getCookie('chat_minimized') === 'true';

    function updateChatState() {
        if (minimized) {
            chatRoom.classList.remove('h-96');
            chatRoom.classList.add('h-11');
            messagesDiv.classList.add('hidden');
            inputContainer.classList.add('hidden');
            minimizeBtn.innerHTML = '&#9660;';
        } else {
            chatRoom.classList.remove('h-11');
            chatRoom.classList.add('h-96');
            messagesDiv.classList.remove('hidden');
            inputContainer.classList.remove('hidden');
            minimizeBtn.innerHTML = '&#9650;';

            // RESET unread
            unreadCount = 0;
            header.classList.remove('chat-blink');
            headerTitle.textContent = 'Chat';

            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    }

    // Initialiseer view
    updateChatState();

    // Toggle minimaliseer
    minimizeBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // voorkomt header-click

        minimized = !minimized;
        setCookie('chat_minimized', minimized ? 'true' : 'false');
        updateChatState();
    });

    function isNearBottom() {
        const threshold = 40; // px
        return messagesDiv.scrollTop + messagesDiv.clientHeight >= messagesDiv.scrollHeight - threshold;
    }

    // Fetch messages
    async function fetchMessages() {
        try {
            const res = await fetch('/chat/messages');
            const data = await res.json();

            // Eerste load = alleen initialiseren
            if (!initialized) {
                lastMessageCount = data.length;
                initialized = true;
            } else {
                if (minimized && data.length > lastMessageCount) {
                    unreadCount += data.length - lastMessageCount;
                    header.classList.add('chat-blink');
                    headerTitle.textContent = `Chat (${unreadCount})`;
                }

                lastMessageCount = data.length;
            }

            // Render messages
            messagesDiv.innerHTML = data.map(m => {
                const typename = (m.chat_type === 'gang') ? m.user.gang?.name ?? 'Gang' : 'All';
                const color = (m.chat_type === 'gang') ? '#BC002D' : 'black';
                return `
                    <div style="color:${color}">
                        ${m.created_at} -
                        <strong>${m.user.name} (${typename}):</strong>
                        ${m.message}
                    </div>
                `;
            }).join('');

            // Alleen autoscroll als chat open is
            if (!minimized) {
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

        } catch (err) {
            console.error('Error fetching messages', err);
        }
    }


    fetchMessages().then(() => {
        lastMessageCount = messagesDiv.children.length;
    });
    setInterval(fetchMessages, 3000);

    async function sendMessage() {
        const toggle = document.getElementById('chat-type-toggle');
        const chatType = toggle && toggle.checked ? 'gang' : 'all';

        const message = input.value.trim();
        if (!message) return;

        await fetch('/chat/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                chat_type: chatType,
            })
        });

        input.value = '';
        fetchMessages();
    }


    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    sendBtn.addEventListener('click', sendMessage);
});
</script>
