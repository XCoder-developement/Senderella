<?php

namespace App\Http\Resources\Api;

use App\Models\Requirment\Requirment;
use App\Models\User\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullPartnerResource extends JsonResource
{
    public $user_id;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user_id = $this->id;
        return [
            "id" => $this->id,
            "images" => count($this->images) == 0 ? null : ImageResource::collection($this->images),
            "name" => $this->name ?? "",
            "age" => $this->user_age ?? "",
            "is_follow" => $this->is_follow(auth()->id()) ?? 0,
            "is_verify" => $this->is_verify ?? 0,
            "trusted" => $this->trusted ?? 0,
            "is_new" => intval($this->is_new) ?? 0,
            "notes" => $this->notes ?? "Not Answered",
            "is_married_before" => intval($this->is_married_before) ?? 0,
            "active" => intval($this->active) ?? "",
            "last_active" => $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : 'active now',

            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "country_id" => intval($this->country_id) ?? "",
            "state_id" => intval($this->state_id) ?? "",
            "country_title" => $this->country?->title ?? "",
            "state_title" => $this->state?->title ?? "",

            "marital_status_id" => intval($this->marital_status_id) ?? null,
            "readiness_for_marriages_id" => intval($this->readiness_for_marriages_id) ?? null,
            "marital_status_title" => $this->marital_status?->title ?? "",
            "marital_status_title" => $this->marital_status?->title ?? "",

            "skin_color_id" => intval($this->color_id) ?? null,
            "education_type_id" => intval($this->education_type_id) ?? null,
            "skin_color_title" => $this->color?->title ?? "",
            "education_type_title" => $this->education_type?->title ?? "",
            "important_for_marriage" => $this->important_for_marriage ?? "Not Answered",
            "partner_specifications"    => $this->partner_specifications ?? "Not Answered",
            "about_me" => $this->about_me ?? "Not Answered",
            "active" => intval($this->active) ?? "",
            "partner_more_info" => UserInformationResource::collection(Requirment::where('answer_type', 1)->get()),
            "questions" => DetailsResource::collection(Requirment::where('answer_type', 2)->get()),
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
            "is_primary" => boolval($this->is_primary) ?? "",
            "is_blurry" => boolval($this->is_blurry) ?? "",
        ];
    }
}

class DetailsResource extends JsonResource
{
    public $user_id;

    public function __construct($resource, $user_id)
    {
        parent::__construct($resource);
        $this->user_id = $user_id;
    }

    public function toArray(Request $request): array
    {
        $user_id = $this->user_id ?? null;
        $info = UserInformation::where('requirment_id', $this->id)->where('type', 2)->where('user_id', $user_id)->first()?->value('answer');
        return [
            'id' => $this->id,
            'question' => strval($this->title) ?? "",
            'answer' => $info ?? "Not Answered",
        ];
    }
}


class UserInformationResource extends JsonResource
{
    public $user_id;

    public function __construct($resource, $user_id)
    {
        parent::__construct($resource);
        $this->user_id = $user_id;
    }

    public function toArray(Request $request): array
    {
        $user_id = $this->user_id ?? null;
        $ques = UserInformation::where('requirment_id', $this->id)->where('type', 1)->where('user_id', $user_id)->first()?->value('answer');

        return [
            "id" => $this->id,
            "title" => ($this->requirment?->title) ?? "",
            "value" => ($ques)  ?? "Not Answered",

            "title_id" => intval($this->requirment_id) ?? "",
            "value_id" => intval($this->requirment_item_id) ?? "",
        ];
    }
}
