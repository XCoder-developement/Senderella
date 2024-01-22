<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Api\TitleResource;
use App\Http\Resources\Api\CountryResource;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->user_age ?? "",
            "image" => $this->image_link ?? "",
            'trusted' => $this->trusted,
            'is_verify' => $this->is_verify,
            'country' => new UserCountryResource($this->country),
            'city' => new UserStateResource($this->state),
        ];
    }
}
