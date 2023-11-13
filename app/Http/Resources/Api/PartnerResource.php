<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
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
            "images" => ImageResource::collection($this->images) ?? "",
            "name"=>$this->name ??"",
            "age"=>$this->age??"",
            "trusted"=>$this->trusted??"",
            "is_new" => intval($this->is_new),
            "country_id" => $this->country_id ?? null,
            "state_id" => $this->state_id ?? null,
            "country_title" => $this->country->title ?? "",
            "state_title" => $this->state->title ?? "",
            "active"=>$this->active??"",
            "partner_more_info"=>PartnerMoreInfo::collection($this->partner_more_info),
        ];
    }
}

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "image" => $this->image_link ?? "",
        ];
    }
}
