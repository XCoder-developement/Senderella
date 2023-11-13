<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => new PostOwnerResource($this->user_id),
            'post' => $this->post,
            'duration' => $this->created_at,
            // 'comments_count' => $this->commentsCount,
            // 'likes_count' => $this->likesCount,
            'comments' => commentResource::collection($this->comments),
        ];
    }
}
