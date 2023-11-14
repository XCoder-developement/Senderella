<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullPartnerResource extends JsonResource
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
            "last_active"=>$this->last_active ?? '',
            "is_follow"=>$this->is_follow ??0,
            "is_verify"=>$this->is_verify ??0,
            "trusted"=>$this->trused ??0,
            "is_new"=>intval($this->is_new)??0,
            "notes" =>$this->notes ??"",
            "is_married_before"=>intval ($this->is_married_before),

            "weight"=>$this->weight ??"",
            "height"=>$this->height ??"",
            "country_id"=>$this->country_id ?? null,
            "State_id"=>$this->State_id ??null,
            "country_title"=>$this->country_title ??"",
            "state_title"=>$this->state_title,


            "marital_status_id" =>$this->marital_status_id??null ,
            "readiness_for_marriages_id" =>$this->readiness_for_marriage_id??null,
            "marital_status_title" => $this->marital_status->title ?? "",
            "marital_status_title" => $this->marital_status->title ?? "",

            "skin_color_id" => $this->color_id ?? null,
            "education_type_id" => $this->education_type_id ?? null,
            "skin_color_title" => $this->color->title ?? "",
            "education_type_title" => $this->education_type->title ?? "",

            "active"=>intval($this->active)??"",
            "partner_more_info"=>UserInformationResource::collection($this->informations),
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

class UserInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "title" => $this->requirment_id ?? "",
            "value" => $this->requirment_item_id ?? "",
        ];
    }
}
