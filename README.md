# AMR Chat 💬

A real-time chat application built with Laravel, Alpine.js, and Laravel Reverb.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat&logo=alpine.js)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat&logo=tailwind-css)
![Laravel Reverb](https://img.shields.io/badge/Reverb-WebSocket-6875F5?style=flat)
![Redis](https://img.shields.io/badge/Redis-Queue-DC382D?style=flat&logo=redis)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql)

---

## ✨ Features

| Feature                     | Status |
| --------------------------- | ------ |
| Private Chat                | ✅     |
| Group Chat                  | ✅     |
| Real-time Messaging         | ✅     |
| Typing Indicator            | ✅     |
| Seen Status                 | ✅     |
| File & Image Sharing        | ✅     |
| Voice / Video Call (WebRTC) | ✅     |
| Message Reply               | ✅     |
| Message Reaction            | ✅     |
| User Search                 | ✅     |
| Online / Offline Status     | ✅     |
| Unread Badge                | ✅     |
| Profile Update & Avatar     | ✅     |
| Dark / Light Mode           | ✅     |
| Queue Support (Horizon)     | ✅     |
| Browser Notification        | 🔜     |

---

## 🛠 Tech Stack

| Layer      | Technology                 |
| ---------- | -------------------------- |
| Backend    | Laravel                    |
| Frontend   | Blade + Alpine.js          |
| Styling    | Tailwind CSS               |
| Real-time  | Laravel Reverb (WebSocket) |
| Queue      | Redis + Laravel Horizon    |
| Database   | MySQL                      |
| Video Call | WebRTC                     |
| Auth       | Laravel Breeze             |
| API Auth   | Laravel Sanctum            |

---

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL
- Redis

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/karmokardev/amr-chat.git
cd amr-chat
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure `.env`

```env
APP_NAME="AMR Chat"
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Dhaka

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amr_chat
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=redis
CACHE_STORE=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

REVERB_APP_ID=1
REVERB_APP_KEY=amrchatkey
REVERB_APP_SECRET=amrchatsecret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 5. Database setup

```bash
php artisan migrate
php artisan storage:link
```

### 6. Build assets

```bash
npm run build
```

---

## ▶️ Running the Application

Run these commands in **3 separate terminals**:

```bash
# Terminal 1 — Laravel Server
php artisan serve

# Terminal 2 — Reverb WebSocket Server
php artisan reverb:start

# Terminal 3 — Queue Worker (Horizon)
php artisan horizon
```

Then visit: **http://localhost:8000**

---

## 📁 Database Schema

```
users
├── id, uuid, name, username, email
├── avatar, is_online, last_seen_at
└── created_at, updated_at

chats
├── id, uuid, type (private/group)
├── name, avatar, created_by
├── last_message_id
└── created_at, updated_at

chat_members
├── id, chat_id, user_id
├── role (owner/member)
├── last_read_message_id
├── is_muted, is_archived
└── joined_at, timestamps

messages
├── id, chat_id, sender_id
├── reply_to_id, type, message
├── media_id, is_edited, edited_at
├── is_deleted_for_everyone
└── timestamps, deleted_at

media
├── id, uploaded_by, disk, path
├── original_name, mime_type
├── extension, size, thumbnail_path
└── timestamps

message_reads
├── id, message_id, user_id
└── read_at

message_reactions
├── id, message_id, user_id
├── emoji
└── timestamps

call_rooms
├── id, chat_id, created_by
├── type (audio/video)
├── status (waiting/active/ended)
├── started_at, ended_at
├── duration_seconds
└── timestamps

call_participants
├── id, call_room_id, user_id
├── joined_at, left_at
├── is_audio_enabled, is_video_enabled
├── status
└── timestamps
```

---

## 🔄 Real-time Flow

```
HTTP Request
    │
    ├── Controller
    │       │
    │       ├── DB Save
    │       └── Queue Job dispatch
    │                   │
    │                   └── Reverb broadcast
    │                           │
    │                           └── Alpine.js listener
    │                                   │
    │                                   └── UI update (real-time)
```

---

## 📡 WebSocket Channels

| Channel                      | Event                 | Purpose          |
| ---------------------------- | --------------------- | ---------------- |
| `private-chat.{id}`          | `message.sent`        | New message      |
| `private-chat.{id}`          | `call.initiated`      | Incoming call    |
| `private-chat.{id}`          | `call.ended`          | Call ended       |
| `private-chat.{id}`          | `message.reacted`     | Reaction update  |
| `private-call.{id}.{userId}` | `call.signal`         | WebRTC signaling |
| `online-status`              | `user.status.changed` | Online/offline   |

---

## 🎨 Brand

- **Primary Color:** `#D97757`
- **Theme:** Dark / Light mode toggle
- **Font:** Figtree

---

## 📊 Queue Monitor

Horizon dashboard available at:

```
http://localhost:8000/horizon
```

---

## 👤 Author

**Hridoy Karmokar**

- GitHub: [@karmokardev](https://github.com/karmokardev)

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
