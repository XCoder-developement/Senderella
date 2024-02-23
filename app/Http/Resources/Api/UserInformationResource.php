<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
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
                        "title" => strval($this->requirment?->title) ?? "",
                        "value" => strval($this->requirment_item?->title)  ?? __("messages.not_answered"),

                        "title_id" => $this->requirment_id ?? "",
                        "value_id" => intval($this->requirment_item_id),
                    ];
    }
}
