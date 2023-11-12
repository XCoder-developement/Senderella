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
            "country"=>$this->country??"",
            "city"=>$this->city??"",
            "active"=>$this->active??"",
            
        ];
    }
}
