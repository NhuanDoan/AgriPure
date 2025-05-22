const form = document.querySelector(".typing-area"),
    incoming_id = form.querySelector(".incoming_id").value,
    inputField = form.querySelector(".input-field"),
    sendBtn = form.querySelector("button"),
    chatbox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
    e.preventDefault();
};

inputField.focus();

inputField.onkeyup = () => {
    sendBtn.classList.toggle("active", inputField.value.trim() !== "");
};

sendBtn.onclick = () => {
    if(inputField.value.trim() !== ""){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "controllers/chatbot/insert-chat-chatbot.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                inputField.value = "";
                scrollToBottom();
            }
        };
        let formData = new FormData(form);
        xhr.send(formData);
    }
};

function scrollToBottom(){
    chatbox.scrollTop = chatbox.scrollHeight;
}

chatbox.onmouseenter = () => chatbox.classList.add("active");
chatbox.onmouseleave = () => chatbox.classList.remove("active");

inputField.addEventListener("keypress", (e) => {
    if (e.key === "Enter" && inputField.value.trim() !== "") {
        e.preventDefault(); // Ngăn form bị submit mặc định
        sendMessage();
    }
});


setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "controllers/chatbot/get-chat-chatbot.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = xhr.response;
            let isScrolledToBottom = chatbox.scrollHeight - chatbox.clientHeight <= chatbox.scrollTop + 1;
            
            chatbox.innerHTML = data;
            
            if (isScrolledToBottom) {
                scrollToBottom();
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id=" + incoming_id);
}, 500);

