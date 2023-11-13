<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\UserResource;
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
        // $user=User::whereId($this->user_id)->first();
        return [
            'id' => $this->id,
            // 'name' => $this->name,
            // 'trusted' => $this->trusted,
            // 'country_id' => $this->country_id,
            // 'city_id' => $this->city_id,
            'comment' => $this->comment,
            // 'user' => new CommentOwnerResource($this->user),
            // 'likes_count' => $this->likesCount
        ];
    }
}
