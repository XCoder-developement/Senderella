<?php

namespace App\Http\Resources\Api;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $message = ChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->value('message');
        return [
            'chat_id' => $this->id,
            'name'   => $this->name ?? '',
            'message'   => $message ?? '',
        ];
    }
}
