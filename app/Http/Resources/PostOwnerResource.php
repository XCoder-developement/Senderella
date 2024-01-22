<?php

namespace App\Http\Resources;

use App\Models\User\UserImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   $image = UserImage::where('user_id', $this->id)->where('is_primary', 1)->first();
        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? "Cinderella",
            "image" => $image->image_link ?? "",
        ];
    }
}
