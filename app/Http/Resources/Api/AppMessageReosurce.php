<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppMessageReosurce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user_id" => $this->user_id,
            // "name" => $this->name ?? "",
            // "email" => $this->email ?? "",
            // "phone" => $this->phone ?? "",
            // "subject" => $this->subject ?? "",
            "message" => $this->message ?? "",
        ];
    }
}
