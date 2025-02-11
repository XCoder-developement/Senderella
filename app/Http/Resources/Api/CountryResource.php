<?php

namespace App\Http\Resources\Api;

use App\Models\EducationType\EducationType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */



    public function toArray(Request $request): array
    {


        return [
            "id" => $this->id??0,
            "title" => $this->title??"",
            "second_stage" => TitleResource::collection($this->states),
        ];
    }
}
