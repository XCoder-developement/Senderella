<?php

namespace App\Http\Resources\Api;

use App\Models\NewDuration\NewDuration;
use App\Models\User\UserBookmark;
use App\Models\User\UserImage;
use App\Models\User\UserLastShow;
use App\Models\User\UserLike;
use App\Models\User\UserWatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\ImageResource;
use App\Http\Resources\Api\UserInformationResource;
use App\Models\User\UserBlock;

class CustomPartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language');
        $user = auth()->user();
        $duration = NewDuration::first()->new_duration; // getting the duration days for the new tag
        $user_duration = Carbon::parse($this->created_at)->diffInDays(); // getting the duration days for the user
        $user_id = $this->id;
        $active = UserLastShow::where('user_id', $user_id)->value('status') ?? 0;
        $last_active = '';
        if ($active == 0) {
            $last_active_date = UserLastShow::where('user_id', $user_id)->value('end_date');
            $last_active_date = \Carbon\Carbon::parse($last_active_date);
            $last_active = $last_active_date->diffForHumans(null, true) . " " . __("messages.ago");
            if ($locale == 'ar') {
                $last_active =  __("messages.ago") . " " . $last_active_date->diffForHumans(null, true);
            }
        }
        $is_blocked = UserBlock::where('user_id', $this->id)->where('partner_id', $user->id)->first();

        $like_time = UserLike::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at');
        if ($like_time) {
            $like_time = UserLike::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at')->format('Y-m-d');
        } else {
            $like_time = '';
        }
        $favorite_time = UserBookmark::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at');
        if ($favorite_time) {
            $favorite_time = UserBookmark::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at')->format('Y-m-d');
        } else {
            $favorite_time = '';
        }
        $watch_time = UserWatch::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at');
        if ($watch_time) {
            $watch_time = UserWatch::where('user_id', $this->id)->where('partner_id', $user->id)->latest()->value('created_at')->format('Y-m-d');
        } else {
            $watch_time = '';
        }

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
        if ($lat1 == null && $lon1 == null && $lat2 == null && $lon2 == null) {
            $distance = 0;
        } else {
            $distance = calculateDistance($lat1, $lon1, $lat2, $lon2);
            $distance = number_format($distance, 2);
        }

        if ($this->type == 1) {
            return [
                "partner" => [
                    "id" => $this->id,
                    "is_verify" => $this->is_verify ?? 0,
                    "active" => intval($active) ?? "",
                    "last_active" => $last_active ?? '', // $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : '',
                    "images" => $this->images == null ? null : ImageResource::collection($images),
                    "name" => $this->name ?? "",
                    "age" => $this->user_age ?? "",
                    "is_follow" => $this->is_follow($user->id) ?? 0,
                    "trusted" => $this->trusted ?? 0,
                    "is_new" => intval(($user_duration) < $duration),
                    "notes" => $this->notes ?? __("messages.not_answered"),
                    "is_married_before" => intval($this->is_married_before) ?? 0,

                    "weight" => intval($this->weight) ?? "",
                    "height" => intval($this->height) ?? "",
                    "country_id" => intval($this->country_id) ?? "",
                    "flag"  => $this->country?->image_link ?? "",
                    "state_id" => intval($this->state_id) ?? "",
                    "country_title" => $this->country?->title ?? "",
                    "state_title" => $this->state?->title ?? "",
                    "nationality_title" => $this->country?->title ?? "",
                    "distance" => intval($distance) ?? '',
                    "marital_status_id" => intval($this->marital_status_id) ?? null,
                    "readiness_for_marriages_id" => intval($this->readiness_for_marriages_id) ?? null,
                    "marital_status_title" => $this->marital_status?->title ?? "",
                    "marriage_readiness_title" => $this->marriage_readiness?->title ?? "",

                    "skin_color_id" => intval($this->color_id) ?? null,
                    "education_type_id" => intval($this->education_type_id) ?? null,
                    "skin_color_title" => $this->color?->title ?? "",
                    "education_type_title" => $this->education_type?->title ?? "",

                    "visibility"   => intval($this->visibility),

                    "is_favorite" => $this->is_favorite($user->id) ?? 0,
                    "is_blocked" => $is_blocked ? 1 : 0,

                    "like_time" => $like_time ?? '',
                    "favorite_time" => $favorite_time ?? '',
                    "watch_time" => $watch_time ?? '',
                    'show_my_image' => intval($this->my_image()) ?? 0,
                    'show_user_image' => intval($this->user_image()) ?? 0,
                ],
                "type" => intval($this->type) ?? '',
                // "partner_more_info" => UserInformationResource::collection($this->informations),
            ];
        } else if ($this->type == 2) {
            $banners = (array) [];
            $banners[] = [
                "id" => $this->id ?? "",
                "link" => $this->link ?? "",
                "image" => $this->image_link ?? "",
            ];
            return [
                "banners" => $banners,
                "type"  => intval($this->type) ?? '',
            ];
        } else if ($this->type == 3) {
            $text_banners = (array) [];
            $text_banners[] = [
                "id" => $this->id ?? "",
                "text" => $this->text ?? "",
            ];
            return [
                "text_banners" => $text_banners,
                "type"  => intval($this->type) ?? '',
            ];
        }
    }
}


// class ImageResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         return [
//             "id" => $this->id,
//             "image" => $this->image_link ?? "",
//             "is_primary" => boolval($this->is_primary) ?? "",
//             "is_blurry" => boolval($this->is_blurry) ?? "",
//         ];
//     }
// }


// class UserInformationResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         return [
//             "id" => $this->id,
//             "title" => strval($this->requirment?->title) ?? "",
//             "value" => strval($this->requirment_item?->title)  ?? __("messages.not_answered"),

//             "title_id" => $this->requirment_id ?? "",
//             "value_id" => intval($this->requirment_item_id),
//         ];
//     }
// }
