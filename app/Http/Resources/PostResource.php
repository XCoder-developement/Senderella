<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PostOwnerResource;
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
            'post' => $this->post,
            'duration' => $this->date_format,
            'owner' => new PostOwnerResource($this->user), //user relation
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->comments->count(),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
