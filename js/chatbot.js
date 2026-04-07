document.addEventListener("DOMContentLoaded", () => {
    // Determine the base path based on current URL to correctly hit app_chat.php
    let basePath = '/nextgen/chatbot/routes/';

    // Create chatbot HTML structure
    const chatHTML = `
    <div id="ng-chat-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: 'Inter', sans-serif;">
        <!-- Chat Button -->
        <button id="ng-chat-btn" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; border: none; box-shadow: 0 4px 12px rgba(99,102,241,0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: transform 0.2s;">
            <i class="fas fa-comment-dots"></i>
        </button>

        <!-- Chat Container -->
        <div id="ng-chat-container" style="display: none; position: absolute; bottom: 80px; right: 0; width: 350px; height: 450px; background: #fff; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; overflow: hidden; flex-direction: column;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #1e1b4b, #4338ca); color: #fff; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-robot"></i>
                    <span style="font-weight: 600; font-size: 1rem;">Placement Query Bot</span>
                </div>
                <button id="ng-chat-close" style="background: none; border: none; color: #fff; cursor: pointer; font-size: 1.2rem; opacity: 0.8;"><i class="fas fa-times"></i></button>
            </div>
            
            <!-- Messages Area -->
            <div id="ng-chat-messages" style="flex: 1; padding: 1rem; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 0.75rem;">
            </div>

            <!-- Input Area -->
            <div style="padding: 0.75rem; border-top: 1px solid #e2e8f0; background: #fff; display: flex; gap: 0.5rem;">
                <input type="text" id="ng-chat-input" placeholder="Type a message..." style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 9999px; font-size: 0.9rem; outline: none;">
                <button id="ng-chat-send" style="background: linear-gradient(135deg, #059669, #10b981); color: #fff; border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;">
                    <i class="fas fa-paper-plane" style="font-size: 0.85rem;"></i>
                </button>
            </div>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', chatHTML);

    const activateBtn = document.getElementById("ng-chat-btn");
    const closeBtn = document.getElementById("ng-chat-close");
    const chatContainer = document.getElementById("ng-chat-container");
    const messageContainer = document.getElementById("ng-chat-messages");
    const messageInput = document.getElementById("ng-chat-input");
    const sendBtn = document.getElementById("ng-chat-send");

    let chatHistory = [];
    let isInitialized = false;

    // Toggle Chat
    const toggleChat = () => {
        if (chatContainer.style.display === "none") {
            chatContainer.style.display = "flex";
            activateBtn.style.transform = "scale(0)";
            setTimeout(() => activateBtn.style.display = "none", 200);
            messageInput.focus();

            if (!isInitialized) {
                sendChat("Initiate your chat with me! Keep it short.");
                isInitialized = true;
            }
        } else {
            chatContainer.style.display = "none";
            activateBtn.style.display = "flex";
            setTimeout(() => activateBtn.style.transform = "scale(1)", 10);
        }
    };

    activateBtn.addEventListener("click", toggleChat);
    closeBtn.addEventListener("click", toggleChat);

    function appendMessage(text, side) {
        const msgDiv = document.createElement("div");

        let styles = "max-width: 85%; padding: 0.65rem 1rem; border-radius: 12px; font-size: 0.85rem; line-height: 1.4; word-wrap: break-word;";
        if (side === "sender") {
            styles += "background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; align-self: flex-end; border-bottom-right-radius: 2px;";
        } else {
            styles += "background: #fff; color: #334155; border: 1px solid #e2e8f0; align-self: flex-start; border-bottom-left-radius: 2px;";
        }

        msgDiv.style.cssText = styles;
        msgDiv.innerText = text;
        messageContainer.appendChild(msgDiv);
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    async function handleSendMessage() {
        const text = messageInput.value.trim();
        if (text !== "") {
            appendMessage(text, "sender");
            messageInput.value = "";
            await sendChat(text);
        }
    }

    async function sendChat(text) {
        // Show typing indicator
        const typingId = 'typing-' + Date.now();
        const typingDiv = document.createElement("div");
        typingDiv.id = typingId;
        typingDiv.style.cssText = "background: #fff; color: #94a3b8; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.8rem; align-self: flex-start; border-bottom-left-radius: 2px;";
        typingDiv.innerHTML = '<i class="fas fa-ellipsis-h fa-fade"></i>';
        messageContainer.appendChild(typingDiv);
        messageContainer.scrollTop = messageContainer.scrollHeight;

        try {
            const response = await axios.post(
                basePath + "app_chat.php",
                {
                    message: text,
                    history: chatHistory
                }
            );

            // Remove typing indicator
            const tDiv = document.getElementById(typingId);
            if (tDiv) tDiv.remove();

            const reply = response.data.reply;
            appendMessage(reply, "receiver");

            chatHistory.push({ role: 'user', parts: [{ text: text }] });
            chatHistory.push({ role: 'model', parts: [{ text: reply }] });
        } catch (error) {
            // Remove typing indicator
            const tDiv = document.getElementById(typingId);
            if (tDiv) tDiv.remove();

            const errorMsg = error.response?.data?.reply || "Error: AI System unavailable. Please try again later.";
            appendMessage(errorMsg, "receiver");
            console.error("Chat Server Error:", error.response?.data || error.message);
        }
    }

    sendBtn.addEventListener("click", handleSendMessage);
    messageInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") handleSendMessage();
    });
});
