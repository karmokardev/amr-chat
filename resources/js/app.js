import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    // search
    Alpine.data("userSearch", () => ({
        query: "",
        results: [],
        isSearching: false,

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }

            this.isSearching = true;

            try {
                const response = await fetch(
                    `/users/search?q=${encodeURIComponent(this.query)}`,
                    {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]',
                            ).content,
                        },
                    },
                );
                this.results = await response.json();
            } catch (err) {
                this.results = [];
            } finally {
                this.isSearching = false;
            }
        },

        async startChat(userId) {
            const response = await fetch("/chats/private", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ user_id: userId }),
            });

            if (response.redirected) {
                window.location.href = response.url;
            } else {
                const data = await response.json();
                if (data.id) {
                    window.location.href = `/chats/${data.id}`;
                }
            }
        },

        clear() {
            this.query = "";
            this.results = [];
        },
    }));

    // chat
    Alpine.data("chatApp", (chatId) => ({
        chatId: chatId,
        newMessage: "",
        messages: [],
        typingText: "",
        typingTimeout: null,

        // Call state
        replyTo: null,
        incomingCall: null,
        activeCall: null,
        callRoom: null,
        isAudioEnabled: true,
        isVideoEnabled: true,
        localStream: null,
        peerConnection: null,
        isSending: false,
        isUploading: false,

        init() {
            this.scrollToBottom();
            this.listenForMessages();
            this.markAsRead();
            this.listenForOnlineStatus();
            window.addEventListener("set-reply", (e) => {
                this.replyTo = e.detail;
            });
        },

        listenForOnlineStatus() {
            window.Echo.channel("online-status").listen(
                ".user.status.changed",
                (e) => {
                    // Sidebar এ online indicator update
                    const indicators = document.querySelectorAll(
                        `[data-user-id="${e.user_id}"]`,
                    );
                    indicators.forEach((el) => {
                        if (e.is_online) {
                            el.classList.remove("bg-gray-300");
                            el.classList.add("bg-green-400");
                        } else {
                            el.classList.remove("bg-green-400");
                            el.classList.add("bg-gray-300");
                        }
                    });
                },
            );
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById("messageContainer");
                if (container) container.scrollTop = container.scrollHeight;
            });
        },
        setReply(message) {
            this.replyTo = message;
            this.$nextTick(() => {
                document
                    .querySelector('input[placeholder="Type a message..."]')
                    .focus();
            });
        },

        clearReply() {
            this.replyTo = null;
        },

        // send message
        async sendMessage() {
            if (!this.newMessage.trim() || this.isSending) return;

            this.isSending = true;

            try {
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
                        reply_to_id: this.replyTo?.id ?? null,
                    }),
                });

                const message = await response.json();
                this.messages.push(message);
                this.replyTo = null;
                this.newMessage = "";
                this.scrollToBottom();
            } catch (err) {
                alert("Message send failed. Try again.");
            } finally {
                this.isSending = false;
            }
        },

        // send file
        async sendFile(file) {
            if (this.isUploading) return;
            this.isUploading = true;

            try {
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
            } catch (err) {
                alert("File upload failed. Try again.");
            } finally {
                this.isUploading = false;
            }
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
                .listen(".call.initiated", (e) => {
                    // নিজের call নিজে দেখবে না
                    const myId = parseInt(
                        document.querySelector('meta[name="user-id"]')
                            ?.content ?? 0,
                    );
                    if (e.caller.id !== myId) {
                        this.incomingCall = e;
                    }
                })
                .listenForWhisper("typing", (e) => {
                    this.typingText = `${e.name} is typing...`;
                    clearTimeout(this.typingTimeout);
                    this.typingTimeout = setTimeout(() => {
                        this.typingText = "";
                    }, 2000);
                })
                .listen(".call.ended", (e) => {
                    if (this.activeCall) {
                        this.peerConnection?.close();
                        this.localStream?.getTracks().forEach((t) => t.stop());
                        this.activeCall = null;
                        this.callRoom = null;
                        this.peerConnection = null;
                        this.localStream = null;
                        alert("Call ended.");
                    }
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

            // Sidebar এ unread badge clear করো
            const sidebar = document.querySelector(
                `a[href="/chats/${this.chatId}"] .bg-\\[\\#D97757\\].rounded-full.w-5`,
            );
            if (sidebar) sidebar.remove();
        },

        // Call methods
        async startCall(type) {
            const response = await fetch(`/chats/${this.chatId}/call`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ type }),
            });

            const data = await response.json();
            this.callRoom = data.call_room;
            this.activeCall = true;

            await this.setupLocalStream(type === "video");
            await this.createPeerConnection();
            this.listenForSignals();

            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            this.sendSignal({ type: "offer", sdp: offer });
        },

        async acceptCall() {
            await fetch(`/call-rooms/${this.incomingCall.call_room.id}/join`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            });

            this.callRoom = this.incomingCall.call_room;
            this.activeCall = true;
            this.incomingCall = null;

            await this.setupLocalStream(this.callRoom.type === "video");
            await this.createPeerConnection();
            this.listenForSignals();
        },

        rejectCall() {
            this.incomingCall = null;
        },

        async endCall() {
            if (this.callRoom) {
                await fetch(`/call-rooms/${this.callRoom.id}/leave`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                });
            }

            this.peerConnection?.close();
            this.localStream?.getTracks().forEach((t) => t.stop());

            this.activeCall = null;
            this.callRoom = null;
            this.peerConnection = null;
            this.localStream = null;
        },

        async setupLocalStream(video = true) {
            try {
                // browser support check
                if (
                    typeof navigator === "undefined" ||
                    !navigator.mediaDevices ||
                    !navigator.mediaDevices.getUserMedia
                ) {
                    console.error("getUserMedia not supported");

                    alert(
                        "Camera/Microphone API not supported.\nUse HTTPS or localhost.",
                    );

                    return;
                }

                // get media
                this.localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: video,
                });

                // set local video
                this.$nextTick(() => {
                    const localVideo = document.getElementById("localVideo");

                    if (localVideo) {
                        localVideo.srcObject = this.localStream;
                    }
                });
            } catch (error) {
                console.error("Media Device Error:", error);

                alert("Camera/Microphone permission denied.");
            }
        },

        async createPeerConnection() {
            this.peerConnection = new RTCPeerConnection({
                iceServers: [{ urls: "stun:stun.l.google.com:19302" }],
            });

            this.localStream.getTracks().forEach((track) => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            this.peerConnection.ontrack = (event) => {
                this.$nextTick(() => {
                    const remoteVideo = document.getElementById("remoteVideo");
                    if (remoteVideo) remoteVideo.srcObject = event.streams[0];
                });
            };

            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.sendSignal({
                        type: "candidate",
                        candidate: event.candidate,
                    });
                }
            };
        },

        async handleSignal(senderId, signal) {
            if (!this.peerConnection) return;

            if (signal.type === "offer") {
                await this.peerConnection.setRemoteDescription(
                    new RTCSessionDescription(signal.sdp),
                );
                const answer = await this.peerConnection.createAnswer();
                await this.peerConnection.setLocalDescription(answer);
                this.sendSignal({ type: "answer", sdp: answer });
            } else if (signal.type === "answer") {
                await this.peerConnection.setRemoteDescription(
                    new RTCSessionDescription(signal.sdp),
                );
            } else if (signal.type === "candidate") {
                await this.peerConnection.addIceCandidate(
                    new RTCIceCandidate(signal.candidate),
                );
            }
        },

        sendSignal(signal) {
            fetch(`/call-rooms/${this.callRoom.id}/signal`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({
                    signal,
                    target_user_id: this.getOtherUserId(),
                }),
            });
        },

        getOtherUserId() {
            return parseInt(
                document.querySelector('meta[name="other-user-id"]')?.content ??
                    0,
            );
        },

        listenForSignals() {
            const userId = parseInt(
                document.querySelector('meta[name="user-id"]')?.content ?? 0,
            );

            window.Echo.private(`call.${this.callRoom.id}.${userId}`).listen(
                ".call.signal",
                (e) => {
                    this.handleSignal(e.sender_id, e.signal);
                },
            );
        },

        toggleAudio() {
            this.isAudioEnabled = !this.isAudioEnabled;
            this.localStream
                ?.getAudioTracks()
                .forEach((t) => (t.enabled = this.isAudioEnabled));
        },

        toggleVideo() {
            this.isVideoEnabled = !this.isVideoEnabled;
            this.localStream
                ?.getVideoTracks()
                .forEach((t) => (t.enabled = this.isVideoEnabled));
        },
    }));
});

Alpine.start();
