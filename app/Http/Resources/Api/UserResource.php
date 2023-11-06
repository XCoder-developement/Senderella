<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "name" => $this->name ?? "",
            "phone" => $this->phone ?? "",
            "email" => $this->email ?? "",
            "gender" => $this->gender ?? "",
            "type" => $this->type ?? "",
            "birthday_date" => $this->birthday_date ?? "",
            "nationality_id" => $this->nationality_id ?? "",
            "marital_status" => intval($this->marital_status) ?? "",
            "is_married_before" => intval($this->is_married_before) ?? "",
            "readiness_for_marriage" => intval($this->readiness_for_marriage) ?? 0,
            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "notes" => $this->notes ?? "",
            "country_id" => $this->country_id ?? null,
            "state_id" => $this->state_id ?? null,
            "country_title" => $this->country->title ?? "",
            "state_title" => $this->state->title ?? "",

            "verification_code" => $this->verification_code ?? "",
            "verification_type" => $this->verification_type ?? "",


            // "is_verified" => $this->is_verified ?? "",

            "api_token" => $this->api_token ?? "",
            "device_token" => $this->user_device->device_token ?? "",
            "images" => ImageResource::collection($this->images) ?? "",
            
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
