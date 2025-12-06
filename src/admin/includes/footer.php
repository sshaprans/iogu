
<div class="ai-widget">
    <div class="ai-chat-window" id="aiChat">
        <div class="ai-header">
            <span>✨ Gemini Assistant</span>
            <span style="cursor:pointer;" onclick="toggleAI()">✕</span>
        </div>
        <div class="ai-body" id="aiMessages">
            <div class="ai-msg ai-msg-bot">Вітаю! Я ваш ШІ-асистент. Я можу допомогти з адмінкою або проаналізувати логи.</div>
        </div>
        <div class="ai-footer">
            <input type="text" class="ai-input" id="aiInput" placeholder="Напишіть запитання..." onkeypress="handleAiEnter(event)">
            <button class="btn btn-green" onclick="sendAiMessage()" style="padding: 5px 10px;">➜</button>
        </div>
    </div>
    <div class="ai-button" onclick="toggleAI()" title="ШІ Помічник">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,2H5A2,2 0 0,0 3,4V18A2,2 0 0,0 5,20H9L12,23L15,20H19A2,2 0 0,0 21,18V4A2,2 0 0,0 19,2M13.88,12.88L12,17L10.12,12.88L6,11L10.12,9.12L12,5L13.88,9.12L18,11L13.88,12.88Z" /></svg>
    </div>
</div>
<!-- Стилі можна винести в CSS -->
<style>

</style>

<div id="ai-chat-widget">
    <div class="chat-header">
        <span>🤖 Асистент ІОГ</span>
        <button id="ai-close-btn">✖</button>
    </div>
    <div id="ai-chat-messages">
    </div>
    <div class="chat-input-area">
        <input type="text" id="ai-input" placeholder="Запитайте про аналітику або логи...">
        <button id="ai-send-btn">Send</button>
    </div>
</div>

<!--<script src="/src/admin/js/ai-assistant.js"></script>-->
<script>
    /**
     * Gemini Chat Controller
     * Об'єднує UI логіку (toggle, render) та бізнес-логіку (історія, API, навігація).
     */

    const GEMINI_API_URL = '/admin/api/gemini.php';
    const STORAGE_KEY = 'gemini_chat_history';

    // Структура історії: [{role: 'user', text: '...'}, {role: 'assistant', text: '...'}]
    let chatHistory = [];

    document.addEventListener('DOMContentLoaded', () => {
        // 1. Завантаження історії при старті
        loadHistory();

        // 2. Якщо історія пуста, запускаємо таймер вітання (10 сек)
        if (chatHistory.length === 0) {
            setTimeout(() => {
                // Перевіряємо ще раз, чи користувач нічого не написав і чи історія досі пуста
                if (chatHistory.length === 0) {
                    fetchWelcomeMessage();
                }
            }, 10000);
        } else {
            // Якщо історія є, рендеримо її одразу
            renderChatHistory();
        }

        // 3. Додаємо слухач подій на Enter для інпута
        const input = document.getElementById('aiInput');
        if (input) {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendAiMessage();
            });
        }
    });

    // --- Логіка Історії (Memory) ---

    function loadHistory() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            try {
                chatHistory = JSON.parse(stored);
            } catch (e) {
                console.error('Помилка парсингу історії чату', e);
                chatHistory = [];
            }
        }
    }

    function saveHistory() {
        // Зберігаємо лише останні 20 повідомлень
        const historyToSave = chatHistory.slice(-20);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(historyToSave));
    }

    function renderChatHistory() {
        const container = document.getElementById('aiMessages');
        if (!container) return;

        container.innerHTML = '';
        chatHistory.forEach(msg => {
            // Конвертуємо роль 'assistant' -> 'bot' для вашого CSS класу
            const type = (msg.role === 'user') ? 'user' : 'bot';
            addMsgToUI(msg.text, type);
        });
        scrollToBottom();
    }

    // --- UI Функції (з вашого скрипта) ---

    function toggleAI() {
        const chat = document.getElementById('aiChat');
        if (!chat) return;

        chat.style.display = (chat.style.display === 'none' || chat.style.display === '') ? 'flex' : 'none';

        if (chat.style.display === 'flex') {
            const input = document.getElementById('aiInput');
            if (input) input.focus();
            scrollToBottom();
        }
    }

    function addMsgToUI(text, type) {
        const container = document.getElementById('aiMessages');
        if (!container) return null;

        const div = document.createElement('div');
        // Використовуємо ваші класи: ai-msg-user або ai-msg-bot
        div.className = `ai-msg ai-msg-${type}`;
        div.innerHTML = text;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return div;
    }

    function scrollToBottom() {
        const container = document.getElementById('aiMessages');
        if (container) container.scrollTop = container.scrollHeight;
    }

    // --- Логіка Спілкування ---

    /**
     * Окремий запит для вітання (не додає повідомлення користувача в чат)
     */
    async function fetchWelcomeMessage() {
        try {
            const response = await fetch(GEMINI_API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: '', // Пусте повідомлення = сигнал для генерації вітання
                    history: []
                })
            });
            const data = await response.json();
            if (data.reply) {
                addMsgToUI(data.reply, 'bot');
                chatHistory.push({ role: 'assistant', text: data.reply });
                saveHistory();

            }
        } catch(e) {
            console.error("Не вдалося отримати вітання", e);
        }
    }

    async function sendAiMessage() {
        const input = document.getElementById('aiInput');
        const text = input.value.trim();
        if (!text) return;

        // 1. Додаємо повідомлення користувача в UI
        addMsgToUI(text, 'user');

        // 2. Зберігаємо в історію
        chatHistory.push({ role: 'user', text: text });
        saveHistory();

        input.value = '';
        input.disabled = true;

        // 3. Показуємо індикатор завантаження
        const loadingDiv = addMsgToUI('<div class="typing-dots"><span></span><span></span><span></span></div>', 'bot');

        try {
            const response = await fetch(GEMINI_API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: text,
                    history: chatHistory // ВАЖЛИВО: Передаємо історію для контексту
                })
            });

            const data = await response.json();
            if (loadingDiv) loadingDiv.remove();

            if (data.error) {
                addMsgToUI('Помилка: ' + data.error, 'bot');
            } else if (data.reply) {
                // Додаємо відповідь бота
                addMsgToUI(data.reply, 'bot');

                // Зберігаємо в історію
                chatHistory.push({ role: 'assistant', text: data.reply });
                saveHistory();

                // ОБРОБКА НАВІГАЦІЇ (SMART ACTION)
                if (data.action === 'navigate' && data.url) {
                    console.log(`Gemini Action: Navigating to ${data.url}`);
                    setTimeout(() => {
                        window.location.href = data.url;
                    }, 1500);
                }
            } else {
                addMsgToUI('Отримана пуста відповідь.', 'bot');
            }
        } catch (error) {
            if (loadingDiv) loadingDiv.remove();
            addMsgToUI('Помилка з\'єднання з сервером.', 'bot');
            console.error(error);
        } finally {
            input.disabled = false;
            input.focus();
        }
    }

    // Експорт функцій у глобальну область видимості,
    // щоб працювали onclick="toggleAI()" у вашому HTML
    window.toggleAI = toggleAI;
    window.sendAiMessage = sendAiMessage;

    document.addEventListener("DOMContentLoaded", function() {
        const chatWidget = document.getElementById('ai-chat-widget');
        const chatMessages = document.getElementById('ai-chat-messages');
        const closeBtn = document.getElementById('ai-close-btn');
        const inputField = document.getElementById('ai-input');
        const sendBtn = document.getElementById('ai-send-btn');

        // 1. ПЕРЕВІРКА: Чи показувати асистента?
        function shouldShowAssistant() {
            const lastClosed = localStorage.getItem('ai_assistant_closed_at');
            if (!lastClosed) return true; // Ніколи не закривали

            const oneWeek = 7 * 24 * 60 * 60 * 1000;
            const now = new Date().getTime();

            // Якщо пройшло більше тижня
            if (now - parseInt(lastClosed) > oneWeek) {
                return true;
            }
            return false;
        }

        // 2. ІНІЦІАЛІЗАЦІЯ
        if (shouldShowAssistant()) {
            chatWidget.style.display = 'flex'; // Показати чат

            // Якщо чат пустий, запускаємо "Рукостискання" з AI
            if (chatMessages.innerHTML.trim() === '') {
                sendToAI('', []); // Відправляємо пусте повідомлення для вітання
            }
        } else {
            chatWidget.style.display = 'none';
        }

        // 3. ЛОГІКА ЗАКРИТТЯ (Хрестик)
        closeBtn.addEventListener('click', function() {
            chatWidget.style.display = 'none';
            localStorage.setItem('ai_assistant_closed_at', new Date().getTime());
            console.log('Асистент вимкнений на 1 тиждень.');
        });

        // 4. ВІДПРАВКА ПОВІДОМЛЕНЬ
        sendBtn.addEventListener('click', handleUserMessage);
        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleUserMessage();
        });

        function handleUserMessage() {
            const text = inputField.value.trim();
            if (!text) return;

            addMessageToUI('user', text);
            inputField.value = '';

            // Збираємо історію (спрощено: останні 2 повідомлення для контексту)
            // В реальному проекті можна зберігати весь масив історії в JS змінній
            const history = [];

            sendToAI(text, history);
        }

        // 5. СПІЛКУВАННЯ З СЕРВЕРОМ
        function sendToAI(message, history) {
            // Індикатор завантаження (якщо це не автозапуск, або можна і для автозапуску)
            if (message) {
                const loadingId = addMessageToUI('ai', 'Thinking...', true);
            }

            fetch('/src/admin/api/gemini.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: message,
                    history: history
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Видаляємо "Thinking..."
                    const loaders = document.querySelectorAll('.ai-loading');
                    loaders.forEach(el => el.remove());

                    if (data.reply) {
                        addMessageToUI('ai', data.reply);
                    }
                })
                .catch(err => {
                    console.error(err);
                    addMessageToUI('ai', 'Вибачте, сталася помилка з\'єднання.');
                });
        }

        function addMessageToUI(role, text, isLoading = false) {
            const div = document.createElement('div');
            div.className = `message ${role} ${isLoading ? 'ai-loading' : ''}`;
            div.innerHTML = text; // PHP вже повертає безпечний HTML з <br> та <strong>
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return div;
        }
    });
</script>
</body>
</html>