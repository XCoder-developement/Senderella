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
            "image" => $this->image_link ?? "",
            "is_verified" => $this->is_verified ?? "",
            "state_id" => $this->state_id ?? null,
            "city_id" => $this->city_id ?? null,
            "zone_id" => $this->zone_id ?? null,

            "state_title" => $this->state->title ?? "",
            "city_title" => $this->city->title ?? "",
            "zone_title" => $this->zone->title ?? "",

            "api_token" => $this->api_token ?? "",
            "invitation_code" => $this->invitation_code ?? "",
            "invite_code" => $this->invite_code ?? "",
            "device_token" => $this->user_device->device_token ?? "",
        ];
    }
}
