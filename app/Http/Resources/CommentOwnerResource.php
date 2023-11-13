<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentOwnerResource extends JsonResource
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
            'name' => $this->name,
            'trusted' => $this->trusted,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            // 'comment' => CommentResource::collection($this->comments),
        ];
    }
}
