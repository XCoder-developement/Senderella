<?php

namespace App\Http\Resources;

use App\Models\Like\Like;
use App\Models\User\User;
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
        $liked = 0;
        $user = auth()->user();
        if ($user) {
            $like = Like::where('user_id', auth()->id())
                ->where('likeable_id', $this->id)
                ->where('likeable_type', 'App\Models\Post\Post')
                ->first();
            $liked = $like ? 1 : 0;
        }
        return [
            'id' => $this->id,
            'post' => $this->post ?? '',
            "is_liked" => $liked ?? 0,
            'duration' => $this->date_format,
            'owner' => new PostOwnerResource($this->user), //user relation
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->comments->count(),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
