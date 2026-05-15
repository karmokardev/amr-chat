<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id'         => $this->message->id,
                'chat_id'    => $this->message->chat_id,
                'sender_id'  => $this->message->sender_id,
                'message'    => $this->message->message,
                'type'       => $this->message->type,
                'created_at' => $this->message->created_at->format('h:i A'),
                'sender'     => [
                    'id'   => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                ],
            ],
        ];
    }
}