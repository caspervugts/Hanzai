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

                    <!-- Slide toggle -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">All</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <!-- De checkbox zelf -->
                            <input type="checkbox" id="chat-type-toggle" class="sr-only peer">
                            
                            <!-- De achtergrond van de toggle -->
                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-focus:ring-2 peer-focus:ring-red-500 
                                        peer-checked:bg-red-500 transition-colors duration-200"></div>
                            
                            <!-- Het schuivende bolletje -->
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md 
                                        transform peer-checked:translate-x-6 transition-transform duration-200"></div>
                        </label>
                        <span class="text-sm">Gang</span>
                    </div>

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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatRoom = document.getElementById('chat-room');
    const messagesDiv = document.getElementById('messages');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send');
    const inputContainer = document.getElementById('chat-input-container');
    const minimizeBtn = document.getElementById('chat-minimize');

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

    // Init state van cookie
    let minimized = getCookie('chat_minimized') === 'true';

    function updateChatState() {
        if (minimized) {
            chatRoom.classList.remove('h-96');
            chatRoom.classList.add('h-11');
            messagesDiv.classList.add('hidden');
            inputContainer.classList.add('hidden');
            minimizeBtn.innerHTML = '&#9660;'; // pijltje naar beneden
        } else {
            chatRoom.classList.remove('h-11');
            chatRoom.classList.add('h-96');
            messagesDiv.classList.remove('hidden');
            inputContainer.classList.remove('hidden');
            minimizeBtn.innerHTML = '&#9650;'; // pijltje omhoog
        }
    }

    // Initialiseer view
    updateChatState();

    // Toggle minimaliseer
    minimizeBtn.addEventListener('click', () => {
        minimized = !minimized;
        setCookie('chat_minimized', minimized);
        updateChatState();
    });

    // Fetch messages
    async function fetchMessages() {
        try {
            const res = await fetch('/chat/messages');
            const data = await res.json();
            messagesDiv.innerHTML = data.map(m => {
                const typename = (m.chat_type === 'gang') ? `${m.user.gang.name}` : 'All';
                const color = (m.chat_type === 'gang') ? '#BC002D' : 'black';
                return `
                    <div style="color:${color}">
                        ${m.created_at} - 
                        <strong>${m.user.name} (${typename}):</strong> 
                        ${m.message}
                    </div>
                `;
            }).join('');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } catch (err) {
            console.error('Error fetching messages', err);
        }
    }

    fetchMessages();
    setInterval(fetchMessages, 3000);

    async function sendMessage() {
        const chatType = document.getElementById('chat-type-toggle').checked ? 'gang' : 'all';
        const message = input.value.trim();

        if (!message) return;
        await fetch('/chat/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                message: input.value, 
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
