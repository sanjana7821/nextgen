

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chatbot</title>
    <link rel="stylesheet" href="/css/output.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="bg-black">
    <div id="main" class="relative h-screen w-screen z-0">
        <div id="chatButton" class="absolute bottom-10 right-2 text-white bg-sky-900 p-2 rounded-lg cursor-pointer z-10  visible">
            Click me
        </div>
        <div id="chat-container"
            class="h-[300px] w-[400px] m-3 bg-white border-4 border-gray-300 flex flex-col shadow-lg rounded-xl overflow-hidden
            absolute bottom-0 right-0 invisible z-20">

            <div id="titleBox" class="flex bg-sky-900 p-2 text-xl justify-center">Placement Query Bot</div>

            <div id="messages" class="flex-1 p-4 overflow-y-auto space-y-2 bg-gray-50">
            </div>

            <div class="p-2 border-t border-gray-200 bg-white flex gap-2">
                <input type="text" id="message-input" placeholder="Type a message..."
                    class="flex-1 border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-amber-500 z-30">
                <button id="send-btn"
                    class="bg-amber-200 text-black px-3 py-1 rounded text-sm font-bold hover:bg-amber-300">
                    Send
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="/js/scripts.js" defer></script>    



</body>

</html>