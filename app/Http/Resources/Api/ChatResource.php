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
        $user = auth()->user();
        $message = ChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->value('message');
        return [
            'chat_id' => $this->id,
            'name'   => $this->name ?? '',
            'message'   => $message ?? '',
            // $message = $user->is_verify == 1 ? $message : substr($message, 0, 5) . encrypt(substr($message, 5)),
        ];
    }
}
