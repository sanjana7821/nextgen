const messageContainer = document.getElementById("messages");
const messageInput = document.getElementById("message-input");
const sendBtn = document.getElementById("send-btn");
const activateBtn = document.getElementById("chatButton");
const chatBox = document.getElementById("chat-container");
const mainScreen = document.getElementById("main");

let chatHistory = [];

function appendMessage(text, side) {
    const msgDiv = document.createElement("div");
    console.log("Appending message:", text, "as", side);
    const baseClasses = "max-w-[80%] rounded px-3 py-2 text-sm shadow-sm";
    const sideClasses =
        side === "sender"
            ? "bg-amber-500 text-white self-end ml-auto"
            : "bg-gray-200 text-gray-800 self-start mr-auto";

    msgDiv.className = `${baseClasses} ${sideClasses}`;
    msgDiv.innerText = text;

    messageContainer.appendChild(msgDiv);

    messageContainer.scrollTop = messageContainer.scrollHeight;
}

async function handleSendMessage(input) {
    const text = input ? input : messageInput.value.trim();
    if (text !== "") {
        appendMessage(text, "sender");
        messageInput.value = "";
        await sendChat(text);
    }
}

async function sendChat(text) {
    try {
        const response = await axios.post(
            "app_chat.php",
            {
                message: text,
                history: chatHistory
            },
        );
        const reply = response.data.reply;
        appendMessage(reply, "receiver");
        console.log(response);

        chatHistory.push({ role: 'user', parts: [{ text: text }] });
        chatHistory.push({ role: 'model', parts: [{ text: reply }] });
    } catch (error) {
        const errorMsg =
            error.response?.data?.reply ||
            "Error: System overloaded. Please wait 30 seconds.";
        appendMessage(errorMsg, "receiver");
        console.error(
            "Server Error:",
            error.response?.data || error.message,
        );
    }
}

window.onload = () => {
    console.log("Document loaded");
    sendChat("Initiate your chat with me! Keep it short.");
    chatBox.classList.toggle("visible");
}

sendBtn.addEventListener("click", handleSendMessage);
activateBtn.addEventListener("click", () => {
    chatBox.classList.add("visible");
    chatBox.classList.remove("invisible");
    activateBtn.classList.add("invisible");
    activateBtn.classList.remove("visible");
    chatBox.focus();
});
mainScreen.addEventListener("pointerdown", () => {
    chatBox.classList.remove("visible");
    chatBox.classList.add("invisible");
    activateBtn.classList.remove("invisible");
    activateBtn.classList.add("visible");
});

messageInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") handleSendMessage();
});
chatBox.addEventListener("pointerdown", (e) => {
    e.stopPropagation();
});
