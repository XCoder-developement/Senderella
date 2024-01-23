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
            // "images" => $this->images !== null && count($this->images) == 0 ? null : ImageResource::collection($this->images),
            "age"=>intval($this->age)??"",
            // "is_follow" => $this->is_follow(auth()->id()) ?? 0,
            "is_verify"=>$this->is_verify ??0,
            "trusted"=>$this->trused ??0,
            "is_new"=>intval($this->is_new)??0,
            "notes" =>$this->notes ??__("messages.not_answered"),
            "is_married_before"=>intval ($this->is_married_before),
            "active" => intval($this->active) ?? "",
            "last_active" => $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : 'active now',

            "weight"=>$this->weight ??"",
            "height"=>$this->height ??"",
            "country_id"=>intval($this->country_id) ?? "",
            "State_id"=>intval($this->state_id) ?? "",
            "country_title" => $this->country?->title ?? "",
            "flag"  => $this->country?->image_link ?? "",
            "state_title" => $this->state?->title ?? "",

            "marriage_readiness_title" => $this->marriage_readiness?->title ?? "",

            "marital_status_id" =>intval($this->marital_status_id)??null ,
            "readiness_for_marriages_id" =>intval($this->readiness_for_marriage_id)??null,
            "marital_status_title" => $this->marital_status?->title ?? "",

            "skin_color_id" => intval($this->color_id) ?? null,
            "education_type_id" => intval($this->education_type_id) ?? null,
            "skin_color_title" => $this->color?->title ?? "",
            "education_type_title" => $this->education_type?->title ?? "",

            "active"=>intval($this->active)??"",
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
            "is_primary" => boolval($this->is_primary) ??"",
            "is_blurry" => boolval($this->is_blurry) ??"",
        ];
    }
}
