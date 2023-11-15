<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingResource extends JsonResource
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
            "youtube" => $this->youtube?? '',
            "instagram" => $this->instagram?? '',
            "facebook" => $this->facebook?? '',
            "linkedin" => $this->linkedin?? '',
            "twitter" => $this->twitter?? '',
            "tikTok" => $this->tikTok?? '',
            "messenger" => $this->messenger?? '',
            "whatsApp" => $this->whatsApp?? '',
            "phone" => $this->phone?? '',
            "email" => $this->email?? '',
            "description" => $this->translate('en')->title ?? '',
        ];
    }
}
