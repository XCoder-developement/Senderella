<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MiniPartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name ??"",
            "images" => ImageResource::collection($this->images) ??"",
            "age"=>$this->age??"",
            "trusted"=>$this->trusted ??"",
            "country"=>$this->country ??"",
            "city"=>$this->city ??"",
            "duration"=>$this->duration ??"",
            "like_status"=>$this->like_status,
            "active"=>$this->active,
        ];
    }
}
