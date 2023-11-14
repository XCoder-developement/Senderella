<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'age' => Carbon::parse($this->birthday_date)->age,
            "image" => $this->image_link ?? "",
            'trusted' => $this->trusted,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
        ];
    }
}
