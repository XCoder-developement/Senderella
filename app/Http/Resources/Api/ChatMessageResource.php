<?php

namespace App\Http\Resources\Api;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id"=> $this->id,
            "chat_id"=> $this->chat_id,
            "user_id"=> $this->user_id,
            "message"=> $this->message ?? null,
            "image"=>  $this->medias ? $this->medias->first()?->image_link : null,
            'type' => $this->message != null ? 1 : 2, //1 message , 2 image
            "is_read"=> $this->is_read,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            // "chat" => new ChatResource($this->chat) ?? null ,
        ];
    }
}
