<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikePostResource extends JsonResource
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
            'post' => $this->post,
            'duration' => $this->created_at,
            'owner' => new PostOwnerResource($this->user),
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->comments->count(),
        ];
    }
}
