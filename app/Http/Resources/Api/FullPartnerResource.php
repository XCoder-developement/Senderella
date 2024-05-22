<?php

namespace App\Http\Resources\Api;

use App\Models\Requirment\Requirment;
use App\Models\RequirmentItem\RequirmentItemTranslation;
use App\Models\User\UserBlock;
use App\Models\User\UserImage;
use App\Models\User\UserInformation;
use App\Models\User\UserLastShow;
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

        $user = auth()->user();
        $user_id = $this->id;
        $active = UserLastShow::where('user_id', $user_id)->value('status') ?? 0;
        $last_active = '';
        if ($active == 0) {
            $last_active_date = UserLastShow::where('user_id', $user_id)->value('end_date');
            $last_active_date = \Carbon\Carbon::parse($last_active_date);
            $last_active = $last_active_date->diffForHumans(null, true);
        }
        $is_blocked = UserBlock::where('user_id', $this->id)->where('partner_id', auth()->id())->first();

        $primaryImages = UserImage::where('user_id', $user_id)
            ->where('is_primary', true)
            ->get();

        $nonPrimaryImages = UserImage::where('user_id', $user_id)
            ->where('is_primary', false)
            ->get();

        $images = $primaryImages->merge($nonPrimaryImages);

        $lat1 = $user->latitude;
        $lon1 = $user->longitude;
        $lat2 = $this->latitude;
        $lon2 = $this->longitude;

        // dd($lat1, $lon1, $lat2, $lon2);

        if ($lat1 != null && $lon1 != null && $lat2 != null && $lon2 != null) {
            $distance = calculateDistance($lat1, $lon1, $lat2, $lon2);
            $distance = number_format($distance, 2);
        }else{
            $distance = 0 ;
        }
        return [
            "id" => $this->id,
            "images" => count($this->images) == 0 ? null : ImageResource::collection($images),
            "name" => $this->name ?? "",
            "age" => $this->user_age ?? "",
            "is_follow" => $this->is_follow($user->id) ?? 0,
            "is_verify" => $this->is_verify ?? 0,
            "trusted" => $this->trusted ?? 0,
            "is_new" => intval($this->is_new) ?? 0,
            "notes" => $this->notes ?? __("messages.not_answered"),
            "is_married_before" => intval($this->is_married_before) ?? __("messages.not_answered"),
            "active" => intval($active) ?? "",
            "last_active" => $last_active ?? '', // $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : 'active now',

            "weight" => intval($this->weight) ?? "",
            "height" => intval($this->height) ?? "",
            "country_id" => intval($this->country_id) ?? "",
            "state_id" => intval($this->state_id) ?? "",
            "country_title" => $this->country?->title ?? "",
            "nationality_title" => $this->country?->title ?? "",
            "flag"  => $this->country?->image_link ?? "",
            "state_title" => $this->state?->title ?? "",

            "marital_status_id" => intval($this->marital_status_id) ?? null,
            "readiness_for_marriages_id" => intval($this->marriage_readiness_id) ?? null,
            "marriage_readiness_title" => $this->marriage_readiness?->title ?? "",
            "marital_status_title" => $this->marital_status?->title ?? "",
            // "marital_status_title" => $this->marital_status?->title ?? "",
            "is_blocked" => $is_blocked ? 1 : 0,

            "distance" => intval($distance) ?? "",
            "skin_color_id" => intval($this->color_id) ?? null,
            "education_type_id" => intval($this->education_type_id) ?? null,
            "skin_color_title" => $this->color?->title ?? "",
            "education_type_title" => $this->education_type?->title ?? "",
            "important_for_marriage" => $this->important_for_marriage ?? __("messages.not_answered"),
            "partner_specifications"    => $this->partner_specifications ?? __("messages.not_answered"),
            "about_me" => $this->about_me ?? __("messages.not_answered"),
            // "active" => intval($this->active) ?? "",

            "is_favorite" => $this->is_favorite($user->id) ?? 0,

            "visibility"   => intval($this->visibility),

            "partner_more_info" => UserInformationResource::collection(Requirment::where('answer_type', 1)->get())->additional(['user_id' => $user_id]),
            "questions" => DetailsResource::collection(Requirment::where('answer_type', 2)->get())->additional(['user_id' => $user_id]),

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

// class DetailsResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {

//         $user_id = $request->partner_id;
//         $info = UserInformation::where('requirment_id', $this->id)->where('type', 2)->where('user_id', $user_id)->first()?->value('answer');
//         return [
//             'id' => $this->id,
//             'question' => strval($this->title) ?? "",
//             'answer' => $info ?? __("messages.not_answered"),
//         ];
//     }
// }


// class UserInformationResource extends JsonResource
// {

//     public function toArray(Request $request): array
//     {
//         $locale = $request->header('Accept-Language');
//         // dd($locale);
//         $user_id = $request->partner_id;
//         $qust = UserInformation::where('requirment_id', $this->id)->where('type', 1)->where('user_id', $user_id)->first()?->requirment_item_id;
//         $ques = RequirmentItemTranslation::where('requirment_item_id', $qust)->where('locale', $locale)->first()?->title;

//         return [
//             "id" => $this->id,
//             "title" => ($this->title) ?? "",
//             "value" => ($ques)  ?? __("messages.not_answered"),

//             "title_id" => intval($this->requirment_id) ?? "",
//             "value_id" => intval($this->requirment_item_id) ?? "",
//         ];
//     }
// }

class DetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user_id = $request->partner_id;
        $info = UserInformation::where('requirment_id', $this->id)->where('type', 2)->where('user_id', $user_id)->first();

        // Check if info is empty and set the answer accordingly
        $answer = $info != null ? $info->answer : __("messages.not_answered");

        return [
            'id' => $this->id,
            'question' => strval($this->title) ?? "",
            'answer' => $answer,
        ];
    }
}

class UserInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language');
        $user_id = $request->partner_id;
        $qust = UserInformation::where('requirment_id', $this->id)->where('type', 1)->where('user_id', $user_id)->first()?->requirment_item_id;
        $ques = RequirmentItemTranslation::where('requirment_item_id', $qust)->where('locale', $locale)->first()?->title;

        return [
            "id" => $this->id,
            "title" => ($this->title) ?? "",
            "value" => ($ques)  ?? __("messages.not_answered"),
            "title_id" => intval($this->requirment_id) ?? "",
            "value_id" => intval($this->requirment_item_id) ?? "",
        ];
    }
}
