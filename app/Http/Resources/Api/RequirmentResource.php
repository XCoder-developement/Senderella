<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this ->id,
            "title"=> $this -> title,
            "type"=> $this -> answer_type,
            "items"=> TitleResource::collection($this->requirment_items),
        ];
    }
}
