<?php

namespace App\Http\Resources\Api;

use App\Models\User\UserImage;
use App\Models\User\UserLastShow;
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
        $user_id = $this->id;
        $active = UserLastShow::where('user_id', $user_id)->value('status') ?? 0;
        $last_active = '';
        if( $active == 0){
            $last_active_date = UserLastShow::where('user_id', $user_id)->value('end_date');
            $last_active_date = \Carbon\Carbon::parse($last_active_date);
            $last_active = $last_active_date->diffForHumans(null, true);
        }
        
        return [
            "id" =>$this->id,
            "name" => $this->name ?? "",
            "phone" => $this->phone ?? "",
            "email" => $this->email ?? "",
            "gender" => intval($this->gender) ?? "",
            "birthday_date" => $this->birthday_date ?? "",
            "nationality_id" => intval($this->nationality_id) ?? "",
            "is_married_before" => intval($this->is_married_before) ?? __("messages.not_answered"),
            "marital_status_id" => intval($this->marital_status_id) ?? "",
            "marriage_readiness_id" => intval($this->marriage_readiness_id) ?? "",
            "education_type_id" => intval($this->education_type_id) ?? "",
            "color_id" => intval($this->color_id) ?? "",

            "skin_color_title" => $this->color?->title ?? "",
            "education_type_title" => $this->education_type?->title ?? "",
            "marital_status_title" => $this->marital_status?->title ?? "",
            // "marital_status_title" => $this->marital_status->title ?? "",
            "marriage_readiness_title" => $this->marriage_readiness?->title ?? "",
            "nationality_title" => $this->country?->title ?? "",
            "important_for_marriage"=> $this->important_for_marriage?? __("messages.not_answered"),
            "partner_specifications"=> $this->partner_specifications?? __("messages.not_answered"),

//weight and rest details
            "weight" => $this->weight ?? "",
            "height" => $this->height ?? "",
            "notes" => $this->notes ?? '',
            "about_me" => $this->about_me ?? '',
            "country_id" => intval($this->country_id) ?? "",
            "state_id" => intval($this->state_id) ?? "",
            "country_title" => $this->country?->title ?? "",
            "flag"  => $this->country?->image_link ?? "",
            "state_title" => $this->state?->title ?? "",

            "percentage" => intval($this->percentage > 100 || $this->percentage >= 90 ? 100 : $this->percentage) ?? "",

            "verification_code" => (string) $this->verification_code ?? "",
            "verification_type" => intval($this->verification_type) ?? "",
            "phone_verify" => intval($this->phone_verify) ,
            "email_verify" => intval($this->email_verify) ,
            "visibility"   => intval($this->visibility),

            // "is_verified" => $this->is_verified ?? "",
            "active" => intval($active) ?? "",
            "last_active" => $last_active ?? '',// $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : '',

            "api_token" => $this->api_token ?? "",
            "device_token" => $this->user_device->device_token ?? "",
            "images" => count($this->images) == 0 ? null : ImageResource::collection($this->images) ,

            "partner_more_info"=>UserInformationResource::collection($this->informations->where('type',1)),

            "questions"=>DetailsResource::collection($this->informations->where('type',2)),

            "search"=>SearchResource::collection($this->search)->last(),


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

class DetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'question'=>strval($this->requirment?->title) ?? "",
            'answer'=>$this->answer ?? '',
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
        "value" => strval($this->requirment_item?->title)  ?? __("messages.not_answered"),

        "title_id" => $this->requirment_id ?? "",
        "value_id" => $this->requirment_item_id ?? __("messages.not_answered"),
        ];
    }
}

class SearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
        "id" => $this->id,
        "word" => strval($this->word) ?? "",
        "state_id" => intval($this->state_id)  ?? '',
        "country_id" => intval($this->country_id)  ?? '',
        "nationality_id"    => intval($this->nationality_id)  ?? '',
        "weight_to"    => intval($this->weight)  ?? '',
        "weight_from"    => intval($this->weight_from)  ?? '',
        "height_to"    => intval($this->height)  ?? '',
        "height_from"    => intval($this->height_from)  ?? '',
        "marital_status_id" => intval($this->marital_status_id)  ?? '',
        "age_from" => intval($this->age_from)  ?? '',
        "age_to"    => intval($this->age_to)  ?? '',
        ];
    }
}
