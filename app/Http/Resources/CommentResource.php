<?php

namespace App\Http\Resources;

use App\Models\Like\Like;
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
        $liked = 0;
        $user = auth()->user();
        if ($user) {
            $like = Like::where('user_id', auth()->id())
                ->where('likeable_id', $this->id)
                ->where('likeable_type', 'App\Models\Comment\Comment')
                ->first();
            $liked = $like ? 1 : 0;
        }
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            "is_liked" => $liked ?? 0,
            'comment_time' => $this->date_format,
            'comment_likes' => $this->likes->count(),
            'user' => new UserCommentInfo($this->user), //user comment info
        ];
    }
}
