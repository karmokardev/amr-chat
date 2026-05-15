import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("chatApp", (chatId) => ({
        chatId: chatId,
        newMessage: "",
        messages: [],
        typingText: "",
        typingTimeout: null,

        init() {
            this.scrollToBottom();
            this.listenForMessages();
            this.markAsRead();
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById("messageContainer");
                if (container) container.scrollTop = container.scrollHeight;
            });
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            const response = await fetch(`/chats/${this.chatId}/messages`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({
                    message: this.newMessage,
                    type: "text",
                }),
            });

            const message = await response.json();
            this.messages.push(message);
            this.newMessage = "";
            this.scrollToBottom();
        },

        async sendFile(file) {
            const formData = new FormData();
            formData.append("file", file);

            const uploadRes = await fetch("/media", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: formData,
            });

            const media = await uploadRes.json();

            const msgRes = await fetch(`/chats/${this.chatId}/messages`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({
                    type: media.type,
                    media_id: media.id,
                    message: media.original_name,
                }),
            });

            const message = await msgRes.json();
            message.media = media;
            this.messages.push(message);
            this.scrollToBottom();
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.sendFile(file);
        },

        sendTyping() {
            window.Echo.private(`chat.${this.chatId}`).whisper("typing", {
                name:
                    document.querySelector('meta[name="user-name"]')?.content ??
                    "User",
            });
        },

        listenForMessages() {
            window.Echo.private(`chat.${this.chatId}`)
                .listen(".message.sent", (e) => {
                    this.messages.push(e.message);
                    this.scrollToBottom();
                    this.markAsRead();
                })
                .listenForWhisper("typing", (e) => {
                    this.typingText = `${e.name} is typing...`;
                    clearTimeout(this.typingTimeout);
                    this.typingTimeout = setTimeout(() => {
                        this.typingText = "";
                    }, 2000);
                });
        },

        async markAsRead() {
            await fetch(`/chats/${this.chatId}/read`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            });
        },
    }));
});

Alpine.start();
