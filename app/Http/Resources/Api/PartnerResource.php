<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\api\RequirmentResource;
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
            "id" => $this->id,
            "images" => ImageResource::collection($this->images) ?? "",
            "name" => $this->name ?? "",
            "age" => $this->user_age ?? "",
            "last_active" => $this->last_active ?? '',
            "is_follow" => $this->is_follow ?? 0,
            "is_verify" => $this->is_verify ?? 0,
            "trusted" => $this->trusted ?? 0,
            "is_new" => intval($this->is_new) ?? 0,
            "notes" => $this->notes ?? "",
            "is_married_before" => intval($this->is_married_before) ?? 0,

            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "country_id" => $this->country_id ?? null,
            "state_id" => $this->state_id ?? null,
            "country_title" => $this->country->title ?? "",
            "state_title" => $this->state->title ?? "",

            "marital_status_id" => $this->marital_status_id ?? null,
            "readiness_for_marriages_id" => $this->readiness_for_marriages_id ?? null,
            "marital_status_title" => $this->marital_status->title ?? "",
            "marital_status_title" => $this->marital_status->title ?? "",

            "skin_color_id" => $this->color_id ?? null,
            "education_type_id" => $this->education_type_id ?? null,
            "skin_color_title" => $this->color->title ?? "",
            "education_type_title" => $this->education_type->title ?? "",

            "active" => intval($this->active) ?? "",
            "partner_more_info" => UserInformationResource::collection($this->informations),
        ];
    }
}

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "image" => $this->image_link ?? "",
        ];
    }
}

class UserInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->requirment->title ?? "",
            "value" => $this->requirment_item->title ?? "",

            "title_id" => $this->requirment_id ?? "",
            "value_id" => $this->requirment_item_id ?? "",
        ];
    }
}
