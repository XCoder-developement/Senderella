<?php

namespace App\Http\Resources\Api;

use App\Models\User\UserImage;
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
            "gender" => intval($this->gender) ?? "",
            "birthday_date" => $this->birthday_date ?? "",
            "nationality_id" => intval($this->nationality_id) ?? "",
            "is_married_before" => intval($this->is_married_before) ?? "",
            "marital_status_id" => intval($this->marital_status_id) ?? "",

            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "notes" => $this->notes ?? "",
            "about_me" => $this->about_me ?? "",
            "country_id" => intval($this->country_id) ?? "",
            "state_id" => intval($this->state_id) ?? "",
            "country_title" => $this->country->title ?? "",
            "state_title" => $this->state->title ?? "",

            "verification_code" => (string) $this->verification_code ?? "",
            "verification_type" => intval($this->verification_type) ?? "",
            "phone_verify" => intval($this->phone_verify) ,
            "email_verify" => intval($this->email_verify) ,


            // "is_verified" => $this->is_verified ?? "",

            "api_token" => $this->api_token ?? "",
            "device_token" => $this->user_device->device_token ?? "",
            "images" => count($this->images) == 0 ? null : ImageResource::collection($this->images) ,

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
            "is_primary" => boolval($this->is_primary)??"",
            "is_blurry" => boolval($this->is_blurry) ??"",
        ];
    }
}
