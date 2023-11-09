<?php

namespace App\Http\Resources\Api;

use App\Models\EducationType\EducationType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     public function fetch_educational_types(){

        $educational_types = EducationType::get();
        $data = TitleResource::collection($educational_types);
        $msg = "fetch_educational_types";

    return $this->dataResponse($msg, $data ,200);
}



    public function toArray(Request $request): array
    {


        return [
            "id" => $this->id??0,
            "title" => $this->title??"",
        ];
    }
}
