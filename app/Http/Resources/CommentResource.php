<?php

namespace App\Http\Resources;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'comment_time' => $this->created_at,
            'comment_likes' => $this->likes->count(),
            'user' => new UserCommentInfo($this->user), //user comment info
        ];
    }
}
