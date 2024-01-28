<?php

namespace App\Events;

use GuzzleHttp\Psr7\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $data;

    public function __construct($message)
    {
        $this->data = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->data['receiver_id']);
    }

    public function broadcastAs()
    {
        return 'SendChatMessage';
    }

    public function broadcastWith()
    {
        return ['title' => $this->data];
    }
}
