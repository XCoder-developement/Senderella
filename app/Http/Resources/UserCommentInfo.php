<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Api\TitleResource;
use App\Http\Resources\Api\CountryResource;
use App\Models\User\UserImage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = UserImage::where('user_id', $this->id)->where('is_primary', 1)->value('image');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->user_age ?? "",
            "image" => $image ?? "",
            'trusted' => $this->trusted,
            'is_verify' => $this->is_verify,
            'country' => $this->country?->title,
            'city' => $this->state->title,
        ];
    }
}
