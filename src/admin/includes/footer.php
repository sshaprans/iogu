</div> <!-- Закриваємо .main-content -->

<!-- --- GLOBAL AI ASSISTANT WIDGET --- -->
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

<style>
    /* Стилі для AI Віджета (додані локально для надійності) */
    .ai-widget { position: fixed; bottom: 30px; right: 30px; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end; font-family: sans-serif; }
    .ai-button { width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.3s; }
    .ai-button:hover { transform: scale(1.1); }
    .ai-button svg { width: 30px; height: 30px; fill: white; }
    .ai-chat-window { width: 350px; height: 500px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); margin-bottom: 15px; display: none; flex-direction: column; overflow: hidden; border: 1px solid #eee; animation: slideUp 0.3s ease-out; }
    .ai-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
    .ai-body { flex-grow: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; font-size: 0.95em; scroll-behavior: smooth; }
    .ai-footer { padding: 10px; border-top: 1px solid #eee; background: white; display: flex; gap: 5px; }
    .ai-input { flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px; }
    .ai-msg { margin-bottom: 12px; padding: 10px 14px; border-radius: 12px; max-width: 85%; line-height: 1.5; word-wrap: break-word; }
    .ai-msg-bot { background: #e0e7ff; color: #333; align-self: flex-start; border-bottom-left-radius: 2px; }
    .ai-msg-user { background: #667eea; color: white; align-self: flex-end; margin-left: auto; border-bottom-right-radius: 2px; }
    .typing-dots span { animation: blink 1.4s infinite both; display: inline-block; width: 4px; height: 4px; background: #555; border-radius: 50%; margin: 0 2px; }
    .typing-dots span:nth-child(2) { animation-delay: 0.2s; } .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes blink { 0% { opacity: 0.2; } 20% { opacity: 1; } 100% { opacity: 0.2; } }
    @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
</style>

<script>
    function toggleAI() {
        const chat = document.getElementById('aiChat');
        chat.style.display = (chat.style.display === 'none' || chat.style.display === '') ? 'flex' : 'none';
        if(chat.style.display === 'flex') {
            document.getElementById('aiInput').focus();
            const container = document.getElementById('aiMessages');
            container.scrollTop = container.scrollHeight;
        }
    }

    function handleAiEnter(e) { if (e.key === 'Enter') sendAiMessage(); }

    function addMsg(text, type) {
        const div = document.createElement('div');
        div.className = `ai-msg ai-msg-${type}`;
        div.innerHTML = text;
        const container = document.getElementById('aiMessages');
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return div;
    }

    async function sendAiMessage() {
        const input = document.getElementById('aiInput');
        const text = input.value.trim();
        if (!text) return;

        addMsg(text, 'user');
        input.value = '';
        input.disabled = true;

        // Індикатор завантаження
        const loadingDiv = addMsg('<div class="typing-dots"><span></span><span></span><span></span></div>', 'bot');

        try {
            const response = await fetch('/admin/api/gemini.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            loadingDiv.remove();

            if (data.error) {
                addMsg('Помилка: ' + data.error, 'bot');
            } else if (data.reply) {
                addMsg(data.reply, 'bot');
            } else {
                addMsg('Отримана пуста відповідь.', 'bot');
            }
        } catch (error) {
            loadingDiv.remove();
            addMsg('Помилка з\'єднання з сервером.', 'bot');
            console.error(error);
        } finally {
            input.disabled = false;
            input.focus();
        }
    }
</script>

</body>
</html>