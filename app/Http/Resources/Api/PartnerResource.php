<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\api\RequirmentResource;
use App\Models\NewDuration\NewDuration;
use Carbon\Carbon;
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
        $duration = NewDuration::first()->new_duration; // getting the duration days for the new tag
        $user_created_at = $this->created_at;
        $user_duration = Carbon::parse($user_created_at)->diffInDays();
        return [
            "id" => $this->id,
            "images" => count($this->images) == 0 ? null : ImageResource::collection($this->images) ,
            "name" => $this->name ?? "",
            "age" => $this->user_age ?? "",
            "last_active" => $this->last_active ?? '',
            "is_follow" => $this->is_follow(auth()->id()) ?? 0,
            "is_verify" => $this->is_verify ?? 0,
            "trusted" => $this->trusted ?? 0,
            "is_new" => intval($user_duration < $duration),
            "notes" => $this->notes ?? "",
            "is_married_before" => intval($this->is_married_before) ?? 0,

            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "country_id" => intval($this->country_id) ?? "",
            "state_id" => intval($this->state_id) ?? "",
            "country_title" => $this->country?->title ?? "",
            "state_title" => $this->state?->title ?? "",

            "marital_status_id" => intval($this->marital_status_id) ?? null,
            "readiness_for_marriages_id" => intval($this->readiness_for_marriages_id) ?? null,
            "marital_status_title" => $this->marital_status?->title ?? "",

            "skin_color_id" => intval($this->color_id) ?? null,
            "education_type_id" => intval($this->education_type_id) ?? null,
            "skin_color_title" => $this->color?->title ?? "",
            "education_type_title" => $this->education_type?->title ?? "",

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
            "is_primary" => boolval($this->is_primary) ??"",
            "is_blurry" => boolval($this->is_blurry) ??"",
        ];
    }
}

class UserInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => strval($this->requirment?->title) ?? "",
            "value" => strval($this->requirment_item?->title)  ?? "",

            "title_id" => $this->requirment_id ?? "",
            "value_id" => intval($this->requirment_item_id),
        ];
    }
}
