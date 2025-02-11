<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\FullPartnerResource;
use App\Models\Like\Like;
use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Enums\NotificationTypeEnum;
use App\Http\Resources\Api\CustomPartnerResource;
use App\Http\Resources\Api\MiniPartnerResource;
use App\Http\Resources\Api\NotificationPartnerResource;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\PartnerResource;
use App\Models\Banner\Banner;
use App\Models\NewDuration\NewDuration;
use App\Models\TextBanner\TextBanner;
use App\Models\User\UserBlock;
use App\Models\User\UserBookmark;
use App\Models\User\UserDevice;
use App\Models\User\UserLike;
use App\Models\User\UserNotification;
use App\Models\User\UserWatch;
use App\Services\SendNotification;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    use ApiTrait;
    public function fetch_all_partners()
    {
        try {
            $banners = [
                'banner1' => Banner::inRandomOrder()->first(),
                'banner2' => Banner::inRandomOrder()->first(),
                'text_banner' => TextBanner::inRandomOrder()->first(),
            ];
            $banner1 = Banner::inRandomOrder()->first();
            $text_banner = TextBanner::inRandomOrder()->first();
            $user = auth()->user();
            // $baner1 = $banners[0];
            // dd(Arr::random($banners));
            if ($banner1 && !$text_banner) {
                unset($banners['text_banner']);
            }
            if ($text_banner && !$banner1) {
                unset($banners['banner1']);
                unset($banners['banner2']);
            }
            $all_partners = User::where('gender', '!=', $user->gender)->whereNot('id', $user->id)->pluck('id')->toArray();
            $blocked = UserBlock::where('user_id', $user->id)->pluck('partner_id')->toArray();
            $active_partners = User::where('gender', '!=', $user->gender)
                ->whereNot('id', $user->id)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

            $disactive_partners = User::where('gender', '!=', $user->gender)
                ->whereNot('id', $user->id)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->orderByDesc(function ($query) {
                    $query->select('end_date')
                        ->from('user_last_shows')
                        ->whereColumn('user_id', 'users.id')
                        ->orderByDesc('end_date')
                        ->limit(1);
                })
                ->pluck('id')
                ->toArray();

            $partnerIds = array_merge($active_partners, $disactive_partners, $all_partners);

            $partnerIds = array_diff($partnerIds, $blocked);
            // Paginate the results after sorting and merging
            $partners = User::whereIn('id', $partnerIds)
                ->orderByRaw("FIELD(id, " . implode(',', $partnerIds) . ")")
                ->paginate(10);

            if ($banner1 || $text_banner) {

                $combinedData = [];
                foreach ($partners as $key => $partner) {
                    $combinedData[] = $partner;
                    if ($key == 3 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                    if ($key == 7 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                }

                // Create a paginator instance manually
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $combinedData,
                    $partners->total(),
                    $partners->perPage(),
                    $partners->currentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $paginator->appends(request()->all());
            } else {

                $paginator = $partners;
            }
            if (!$paginator) {
                $msg = "message.there_is_no_partners";
                return $this->dataResponse($msg, 200);
            }

            $msg = "fetch_all_users";

            return $this->dataResponse($msg, CustomPartnerResource::collection($paginator)->response()->getData(true), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }



    public function fetch_partner_details(Request $request)
    {
        try {

            $rules = [
                "partner_id" => "required|exists:users,id",
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }
            $partner = User::whereId($request->partner_id)->first();

            $msg = "fetch_partner_details";
            return $this->dataResponse($msg, new FullPartnerResource($partner), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_new_partners()
    {
        try {
            $banners = [
                'banner1' => Banner::inRandomOrder()->first(),
                'banner2' => Banner::inRandomOrder()->first(),
                'text_banner' => TextBanner::inRandomOrder()->first(),
            ];
            $banner1 = Banner::inRandomOrder()->first();
            $text_banner = TextBanner::inRandomOrder()->first();
            if ($banner1 && !$text_banner) {
                unset($banners['text_banner']);
            }
            if ($text_banner && !$banner1) {
                unset($banners['banner1']);
                unset($banners['banner2']);
            }
            $user = auth()->user();
            $blocked = UserBlock::where('user_id', $user->id)->pluck('partner_id')->toArray();

            $duration = NewDuration::first()->new_duration; // getting the duration days for the new tag
            // dd(Carbon::now()->subDays($duration)->format('Y-m-d h:m'));
            $actve_new_partners = User::where('id', '!=', $user->id)->where('gender', '!=', $user->gender)
                ->whereDate('created_at', '>', Carbon::now()->subDays($duration)->format('Y-m-d'))
                // ->orderBy('id', 'desc')
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

            $off_new_partners = User::where('id', '!=', $user->id)->where('gender', '!=', $user->gender)->whereDate('created_at', '>', Carbon::now()->subDays($duration))
                ->whereDate('created_at', '>', Carbon::now()->subDays($duration)->format('Y-m-d'))
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->orderByDesc(function ($query) {
                    $query->select('end_date')
                        ->from('user_last_shows')
                        ->whereColumn('user_id', 'users.id')
                        ->orderByDesc('end_date')
                        ->limit(1);
                })
                ->pluck('id')
                ->toArray();

            $combinedPartnersids = array_merge($actve_new_partners, $off_new_partners);
            $combinedPartnersids = array_diff($combinedPartnersids, $blocked);

            $combinedPartners = User::whereIn('id', $combinedPartnersids)
                ->orderByRaw("FIELD(id, " . implode(',', $combinedPartnersids) . ")")
                ->paginate(10);

            if ($banner1 || $text_banner) {

                $combinedData = [];
                foreach ($combinedPartners as $key => $partner) {
                    $combinedData[] = $partner;
                    if ($key == 3 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                    if ($key == 7 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                }

                // Create a paginator instance manually
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $combinedData,
                    $combinedPartners->total(),
                    $combinedPartners->perPage(),
                    $combinedPartners->currentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $paginator->appends(request()->all());
            } else {

                $paginator = $combinedPartners;
            }

            $msg = "fetch_new_partners";
            return $this->dataResponse($msg, CustomPartnerResource::collection($paginator)->response()->getData(true), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }




    public function like_partner(Request $request)
    {
        try {
            $rules = [
                "partner_id" => "required|exists:users,id",
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }
            $user = auth()->user();
            $user_id = auth()->id();
            $partner_id = $request->partner_id;
            $partner = User::whereId($partner_id)->first();
            $type = NotificationTypeEnum::LIKE->value;
            $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';
            // dd($image);
            $like_partner = UserLike::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->first();

            $partner = User::whereId($partner_id)->first();
            if (!$like_partner) {
                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;

                $partner->update(['is_like_shown' => $partner->is_like_shown + 1]);
                $partner->update(['is_notification_shown' => $partner->is_notification_shown + 1]);
                $userId = $user->id;
                $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');
                foreach ($partner_devices as $device) {
                    // dd($device);
                    SendNotification::send($device, __('messages.new_like'), __('messages.new_like'), $type, $userId, url($image) ?? '');
                }
                UserNotification::create([
                    'user_id' => $partner->id,
                    'title' => __('messages.new_like'),
                ]);
                UserLike::create($data);
                //responce
                $msg = "like_partner";
                $data = new PartnerResource($partner);
                return $this->dataResponse($msg, $data, 200);
            } elseif ($like_partner) {

                $like_partner->delete();
                if ($partner->is_like_shown > 0) {
                    $partner->update(['is_like_shown' => $partner->is_like_shown - 1]);
                }

                $type = NotificationTypeEnum::DISLIKE->value;
                $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';
                $userId = $user->id;
                $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');

                foreach ($partner_devices as $device) {
                    // dd($device);
                    SendNotification::send($device, $user->name, __('messages.dislike_you'), $type, $userId, url($image) ?? '');
                }

                $msg = __('messages.partner disliked');
                $data = new PartnerResource($partner);
                return $this->dataResponse($msg, $data, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function block_partner(Request $request)
    {
        try {

            $rules = [ //partner_id
                "partner_id" => "required|exists:users,id",
                // reason
                "reason" => "sometimes",
                //reason_id
                "reason_ids" => "sometimes|array",
                "reason_ids.*" => "sometimes|exists:block_reasons,id",
            ];


            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user_id = auth()->id();
            $partner_id = $request->partner_id;
            $reason_ids = $request->reason_ids;
            $reason = $request->reason;

            $like_partner = UserBlock::where('user_id', $user_id)->where('partner_id',  $partner_id)->first();
            $partner = User::find( $partner_id );
            $user = User::find( $user_id );
            if (!$like_partner) {
                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                $data['text'] =  $reason ?? null;
                $user_block = UserBlock::create($data);

                $user_block->reasons()->attach($reason_ids);

                $title = __('message.block');
            $text = __('message.partner_blocked');
            $type = NotificationTypeEnum::BLOCK->value;
            if (isset($partner->devices) && $partner->devices->count() > 0) {
                foreach ($partner->devices as $user_device) {

                    SendNotification::send(
                        $user_device->device_token,
                        $title,
                        $text,
                        $type,
                        $user_id ,
                        url($user->image_link),
                        '' ,
                        '');
                }
            }

                $msg = __("messages.partner blocked");

                return $this->successResponse($msg, 200);
            } elseif ($like_partner) {
                $like_partner->delete();
                $msg = __('messages.partner unblocked');
                return $this->successResponse($msg, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    // public function bookmark_partner(Request $request)
    // {
    //     try {
    //         $rules = [
    //             "partner_id" => "required|exists:users,id",
    //         ];
    //         $validator = Validator::make(request()->all(), $rules);
    //         if ($validator->fails()) {
    //             return $this->getvalidationErrors("validator");
    //         }

    //         $user_id = auth()->id();
    //         $partner_id = $request->partner_id;

    //         $partner = User::whereId($partner_id)->first();

    //         $bookmark_partner = UserBookmark::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->first();

    //         if ($bookmark_partner) {
    //             UserBookmark::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->delete();
    //             $msg = __('messages.you_removed_this_user__from_your_bookmarks');
    //             $data = new PartnerResource($partner);
    //             return $this->dataResponse($msg, $data, 200);
    //         } else {

    //             $data['user_id'] =  $user_id;
    //             $data['partner_id'] =  $partner_id;
    //             UserBookmark::create($data);

    //             //
    //             $user = auth()->user();
    //             $userId = $user->id;
    //             $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';
    //             $type = 5;
    //             $partner->update(['is_bookmark_shown' => $partner->is_bookmark_shown + 1]);

    //             $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');
    //             foreach ($partner_devices as $device) {
    //                 // dd($device);
    //                 SendNotification::send($device, __('messages.bookmarked_by_user'), __('messages.bookmarked_by_user'), $type, $userId, url($image) ?? '');
    //             }
    //             // UserNotification::create([
    //             //     'user_id' => $partner->id,
    //             //     'title' => __('messages.bookmarked_by_user'),
    //             // ]);

    //             $msg = __('messages.you_bookmarked_this_user');
    //             $data = new PartnerResource($partner);
    //             return $this->dataResponse($msg, $data, 200);
    //         }
    //     } catch (\Exception $ex) {
    //         return $this->returnException($ex->getMessage(), 500);
    //     }
    // }

    public function bookmark_partner(Request $request)
    {
        try {
            $rules = [
                "partner_id" => "required|exists:users,id",
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }

            $user_id = auth()->id();
            $partner_id = $request->partner_id;


            $bookmark_partner = UserBookmark::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->first();

            if ($bookmark_partner) {
                $bookmark_partner->delete();
                $partner = User::whereId($partner_id)->first();
                if ($partner->is_bookmark_shown > 0) {
                    $partner->update(['is_bookmark_shown' => $partner->is_bookmark_shown - 1]);
                }
                $msg = __('messages.you_removed_this_user__from_your_bookmarks');
                $data = new PartnerResource($partner);

                return $this->dataResponse($msg, $data, 200);

            } else {

                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                UserBookmark::create($data);

                $partner = User::whereId($partner_id)->first();
                //
                $user = auth()->user();
                $userId = $user->id;
                $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';
                $type = NotificationTypeEnum::BOOKMARK->value;
                $partner->update(['is_bookmark_shown' => $partner->is_bookmark_shown + 1]);

                $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');
                foreach ($partner_devices as $device) {
                    // dd($device);
                    SendNotification::send($device, __('messages.bookmarked_by_user'), __('messages.bookmarked_by_user'), $type, $userId, url($image) ?? '');
                }
                // UserNotification::create([
                //     'user_id' => $partner->id,
                //     'title' => __('messages.bookmarked_by_user'),
                // ]);

                $msg = "bookmark_partner";
                $data = new PartnerResource($partner);
                return $this->dataResponse($msg, $data, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function user_watch(Request $request)
    {
        try {
            $rules = [
                "partner_id" => "required|exists:users,id",
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }

            $user = auth()->user();
            $user_id = $user->id;
            $partner_id = $request->partner_id;
            $data['user_id'] =  $user->id;
            $data['partner_id'] =  $partner_id;
            // notifiaction_settings
            $type = NotificationTypeEnum::VIEW->value;

            $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';

            $partner = User::whereId($partner_id)->first();
            $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');

            // notifiaction_settings
            $watched_before = UserWatch::where('user_id', $user_id)->where('partner_id', $partner_id)->first();
            if ($watched_before) {
                $watched_before->delete();
                //user watch
                UserWatch::create($data);
            } else {

                UserWatch::create($data);
                //user watch

                if ($partner->id != $user_id) {
                    $userId = $partner->id;
                    $partner->update(['is_watch_shown' => $partner->is_watch_shown + 1]);
                    $partner->update(['is_notification_shown' => $partner->is_notification_shown + 1]);
                    UserNotification::create([
                        'user_id' => $partner->id,
                        'title' => __('messages.someone_viewed'),

                    ]);
                    foreach ($partner_devices as $device) {
                        // dd($device);
                        SendNotification::send($device, __('messages.someone_viewed'), __("messages.someone_viewed"), $type, $userId, url($image) ?? '');
                    }
                }
            }


            //responce
            $msg = "user_watch";
            $data = new PartnerResource($partner);
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function fetch_following()
    {
        try {
            $user = auth()->user();

            // Assuming 'likes' is the relationship for partner being followed
            $following_ids = $user->following()->orderBy('created_at', 'desc')->pluck('partner_id')
                ->reject(function ($partner_id) use ($user) {
                    return $partner_id == $user->id;
                })->toArray();

            if (!empty($following_ids)) {
                $followingUserIds = implode(',', $following_ids); // Convert array to comma-separated string

                $users = User::select('users.*')
                    ->join('user_likes', 'users.id', '=', 'user_likes.partner_id')
                    ->where('gender', '!=', $user->gender)
                    ->whereIn('users.id', $following_ids)
                    ->orderByRaw("FIELD(users.id, $followingUserIds)") // Order by the sequence of IDs in the $followingUserIds array
                    // ->orderBy('user_likes.created_at', 'desc') // Then order by user_likes.created_at
                    ->distinct()
                    ->get();

                // dd($users);

                $msg = "fetch_following";
                return $this->dataResponse($msg, NotificationPartnerResource::collection($users), 200);
            } else {
                $msg = "fetch_following";
                return $this->dataResponse($msg, [], 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_followers()
    {
        try {
            $user = auth()->user();
            $followers_ids = $user->followers()->orderBy('created_at', 'desc')->pluck('user_id')
                ->reject(function ($partner_id) use ($user) {
                    return $partner_id == $user->id;
                })->toArray();
            if (!empty($followers_ids)) {

                $followerUserIds = implode(',', $followers_ids); // Convert array to comma-separated string

                $followers = User::select('users.*')
                    ->join('user_likes', 'users.id', '=', 'user_likes.user_id')
                    ->where('gender', '!=', $user->gender)
                    ->whereIn('users.id', $followers_ids)
                    ->orderByRaw("FIELD(users.id, $followerUserIds)") // Order by the sequence of IDs in the $followingUserIds array
                    // ->orderBy('user_likes.created_at', 'desc') // Then order by user_likes.created_at
                    ->distinct()
                    ->get();
                $msg = "fetch_followers";
                return $this->dataResponse($msg, PartnerResource::collection($followers), 200);
            } else {
                $msg = "fetch_followers";
                return $this->dataResponse($msg, [], 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function fetch_my_block_partners()
    {
        try {
            $user = auth()->user();
            $blocked_ids = $user->blocked->pluck("partner_id")->toArray();
            $blocked = User::whereIn('id', $blocked_ids)->get();
            $msg = "fetch_my_block_partners";
            return $this->dataResponse($msg, PartnerResource::collection($blocked), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_blockers()
    {
        try {
            $user = auth()->user();
            $blocker_ids = $user->blocker->pluck("user_id")->toArray();
            $blocker = User::WhereIn('id', $blocker_ids)->get();
            $msg = "fetch_blockers";
            return $this->dataResponse($msg, PartnerResource::collection($blocker), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function who_i_watch()
    {
        try {
            $user = auth()->user();
            $watched_ids = $user->Watched()->orderBy('created_at', 'desc')->pluck('partner_id')
                ->reject(function ($partner_id) use ($user) {
                    return $partner_id == $user->id;
                })->toArray();

            if (!empty($watched_ids)) {
                $watcheds = implode(',', $watched_ids); // Convert array to comma-separated string


                $watched = User::select('users.*')
                    ->join('user_watches', 'users.id', '=', 'user_watches.partner_id')
                    ->where('gender', '!=', $user->gender)
                    ->whereIn('users.id', $watched_ids)
                    ->orderByRaw("FIELD(users.id, $watcheds)") // Order by the sequence of IDs in the $followingUserIds array
                    // ->orderBy('user_likes.created_at', 'desc') // Then order by user_likes.created_at
                    ->distinct()
                    ->get();

                // dd($watched);
                $msg = "who_i_watch";
                return $this->dataResponse($msg, NotificationPartnerResource::collection($watched), 200);
            } else {
                $msg = "who_i_watch";
                return $this->dataResponse($msg, [], 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }



    public function who_watch_my_account()
    {
        try {
            $user = auth()->user();
            $watcher_ids = $user->watcher()->orderBy('created_at', 'desc')->where('partner_id', $user->id)->pluck("user_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();


            if (!empty($watcher_ids)) {

                $watchersIds = implode(',', $watcher_ids); // Convert array to comma-separated string

                // $watcher = User::whereIn('id', $watcher_ids)->get();
                $watcher = User::select('users.*')
                    ->join('user_watches', 'users.id', '=', 'user_watches.user_id')
                    ->where('gender', '!=', $user->gender)
                    ->whereIn('users.id', $watcher_ids)
                    ->orderByRaw("FIELD(users.id, $watchersIds)") // Order by the sequence of IDs in the $followingUserIds array
                    // ->orderBy('user_likes.created_at', 'desc') // Then order by user_likes.created_at
                    ->distinct()
                    ->get();

                $msg = "who_watch_my_account";

                return $this->dataResponse($msg, PartnerResource::collection($watcher), 200);
            } else {
                $msg = "who_watch_my_account";
                return $this->dataResponse($msg, [], 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function who_i_favorite()
    {

        try {
            $user = auth()->user();
            $favorited_ids = $user->favorited()->orderBy('created_at', 'desc')->pluck("partner_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();

            if (!empty($favorited_ids)) {
                $favoriteds = implode(',', $favorited_ids); // Convert array to comma-separated string

                $favorited = User::select('users.*')
                    ->join('user_bookmarks', 'users.id', '=', 'user_bookmarks.partner_id')
                    ->where('gender', '!=', $user->gender)
                    ->whereIn('users.id', $favorited_ids)
                    ->orderByRaw("FIELD(users.id, $favoriteds)") // Order by the sequence of IDs in the $followingUserIds array
                    // ->orderBy('user_bookmarks.created_at', 'desc') // Then order by user_likes.created_at
                    ->distinct()
                    ->get();

                $msg = "who_i_favorite";
                return $this->dataResponse($msg, NotificationPartnerResource::collection($favorited), 200);
            } else {
                $msg = "who_i_favorite";
                return $this->dataResponse($msg, [], 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function who_favorite_me() {
        try {
            $user = auth()->user();

            // Get favorite IDs, excluding the current user
            $favorite_ids = $user->favorited_by()
                ->orderBy('created_at', 'desc')
                ->pluck("user_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();

            if (empty($favorite_ids)) {
                // If no favorites, return empty response
                $msg = "who_favorite_me";
                return $this->dataResponse($msg, [], 200);
            }

            // Convert array to comma-separated string
            $favorites = implode(',', $favorite_ids);

            // Query users who favorited the current user
            $favorite = User::select('users.*')
                ->join('user_bookmarks', 'users.id', '=', 'user_bookmarks.user_id')
                ->where('gender', '!=', $user->gender)
                ->whereIn('users.id', $favorite_ids)
                ->orderByRaw("FIELD(users.id, $favorites)") // Order by the sequence of IDs in the $favorite_ids array
                ->distinct()
                ->get();

            $msg = "who_favorite_me";
            return $this->dataResponse($msg, PartnerResource::collection($favorite), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function most_compatible_partners()
    {
        try {
            $banners = [
                'banner1' => Banner::inRandomOrder()->first(),
                'banner2' => Banner::inRandomOrder()->first(),
                'text_banner' => TextBanner::inRandomOrder()->first(),
            ];
            $banner1 = Banner::inRandomOrder()->first();
            $text_banner = TextBanner::inRandomOrder()->first();
            if ($banner1 && !$text_banner) {
                unset($banners['text_banner']);
            }
            if ($text_banner && !$banner1) {
                unset($banners['banner1']);
                unset($banners['banner2']);
            }

            $user = auth()->user();
            $blocked = UserBlock::where('user_id', $user->id)->pluck('partner_id')->toArray();

            // dd(Carbon::parse($user->birthday_date)->subYears(5));
            $mdate = Carbon::parse($user->birthday_date)->subYears(5)->format('Y-m-d');
            $fdate = Carbon::parse($user->birthday_date)->addYears(5)->format('Y-m-d');
            // dd($date, $fdate);
            if ($user->gender == 1) { // if it's male it will show the female that compitable with him
                $compatible_partner = User::where('gender', 2)->whereNotIn('id', $blocked)->where('height', '<=', $user->height)
                    // ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '>=', $mdate)
                    ->whereDate('birthday_date', '<=', $user->birthday_date);
            } else {
                $compatible_partner = User::where('gender', 1)->whereNotIn('id', $blocked)->where('height', '>=', $user->height)
                    // ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '<=', $fdate)->whereDate('birthday_date', '>=', $user->birthday_date);
            }

            // $page = $request->page; // Set the page number
            // $perPage = 10; // Set the number of items per page
            // $offset = ($page - 1) * $perPage;

            // $compatible_partner = $compatible_partner->slice($offset, $perPage);

            $compatible_partner = $compatible_partner->paginate(10);
            if ($banner1 || $text_banner) {

                $combinedData = [];
                foreach ($compatible_partner as $key => $partner) {
                    $combinedData[] = $partner;
                    if ($key == 3 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                    if ($key == 7 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                }

                // Create a paginator instance manually
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $combinedData,
                    $compatible_partner->total(),
                    $compatible_partner->perPage(),
                    $compatible_partner->currentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $paginator->appends(request()->all());
            } else {

                $paginator = $compatible_partner;
            }
            $msg = "most_compatible_partners";
            // dd($compatible_partner);
            return $this->dataResponse($msg, CustomPartnerResource::collection($paginator)->response()->getData(true), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_most_liked_partners()
{
    try {
        // Randomly select banners
        $banner1 = Banner::inRandomOrder()->first();
        $text_banner = TextBanner::inRandomOrder()->first();
        $user = auth()->user();
        $blocked = UserBlock::where('user_id', $user->id)->pluck('partner_id')->toArray();
        // Retrieve active and inactive partner counts
        $active_partner_counts = UserLike::groupBy('partner_id')
            ->select('partner_id', DB::raw('COUNT(partner_id) as count'))
            ->whereIn('user_id', function ($query) {
                $query->select('user_id')->from('user_last_shows')->where('status', 1);
            })
            ->orderBy('count', 'desc')
            ->pluck('partner_id')
            ->toArray();
        $disactive_partner_counts = UserLike::groupBy('partner_id')
            ->select('partner_id', DB::raw('COUNT(partner_id) as count'))
            ->whereIn('user_id', function ($query) {
                $query->select('user_id')->from('user_last_shows')->where('status', 0);
            })
            ->orderBy('count', 'desc')
            ->pluck('partner_id')
            ->toArray();
        // Combine active and inactive partner counts
        $mostLikedPartnerIds = array_merge($active_partner_counts, $disactive_partner_counts);
        // dd($mostLikedPartnerIds);
        // Fetch partners based on most liked partner IDs, excluding blocked users
        $mostLikedPartners = User::whereIn('id', array_values($mostLikedPartnerIds))
            ->whereNotIn('id', $blocked)
            ->where('gender', '!=', $user->gender)
            ->paginate(10);
        // Combine partners and banners
        $combinedData = $mostLikedPartners->items();
        if ($banner1 && $text_banner) {
            $combinedData[] = $banner1;
            $combinedData[] = $text_banner;
        }
        $msg = "fetch_most_liked_partners";
        $data = CustomPartnerResource::collection($combinedData)->response()->getData(true);
        return $this->dataResponse($msg, $data, 200);
    } catch (\Exception $ex) {
        // Log exception for debugging
        Log::error($ex);
        return $this->returnException($ex->getMessage(), 500);
    }
}


    public function fetch_nearst_partners(Request $request)
    {

        try {
            $rules = [
                "longitude" => "sometimes",
                "latitude" => "sometimes",
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }
            $banners = [
                'banner1' => Banner::inRandomOrder()->first(),
                'banner2' => Banner::inRandomOrder()->first(),
                'text_banner' => TextBanner::inRandomOrder()->first(),
            ];
            $banner1 = Banner::inRandomOrder()->first();
            $text_banner = TextBanner::inRandomOrder()->first();

            if ($banner1 && !$text_banner) {
                unset($banners['text_banner']);
            }
            if ($text_banner && !$banner1) {
                unset($banners['banner1']);
                unset($banners['banner2']);
            }

            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distance = 5;    //* the redaius is about 5meter

            $distanceInDegrees = $distance / (111.32 * 1000);

            $user = auth()->user();
            $blocked = UserBlock::where('user_id', $user->id)->pluck('partner_id')->toArray();

            $active_nearst_partners = User::where('id', '!=', $user->id)
                ->whereBetween('latitude', [$latitude - $distanceInDegrees, $latitude + $distanceInDegrees])
                ->whereBetween('longitude', [$longitude - $distanceInDegrees, $longitude + $distanceInDegrees])
                ->where('visibility', 0)
                ->where('gender', '!=', $user->gender)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

            $disactive_nearst_partners = User::where('id', '!=', $user->id)
                ->whereBetween('latitude', [$latitude - $distanceInDegrees, $latitude + $distanceInDegrees])
                ->whereBetween('longitude', [$longitude - $distanceInDegrees, $longitude + $distanceInDegrees])
                ->where('visibility', 0)
                ->where('gender', '!=', $user->gender)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->orderByDesc(function ($query) {
                    $query->select('end_date')
                        ->from('user_last_shows')
                        ->whereColumn('user_id', 'users.id')
                        ->orderByDesc('end_date')
                        ->limit(1);
                })
                ->pluck('id')
                ->toArray();

            $nearst_partnersids = array_merge($active_nearst_partners, $disactive_nearst_partners);
            // $nearst_partnersids = array_diff($nearst_partnersids, $blocked);

            $nearst_partners = User::whereIn('id', $nearst_partnersids)->whereNotIn('id', $blocked)
                ->orderByRaw("FIELD(id, " . implode(',', $nearst_partnersids) . ")")
                ->paginate(10);

            if ($banner1 || $text_banner) {

                $combinedData = [];
                foreach ($nearst_partners as $key => $partner) {
                    $combinedData[] = $partner;
                    if ($key == 3 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                    if ($key == 7 && $banners) {
                        $combinedData[] = Arr::random($banners);
                    }
                }

                // Create a paginator instance manually
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $combinedData,
                    $nearst_partners->total(),
                    $nearst_partners->perPage(),
                    $nearst_partners->currentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $paginator->appends(request()->all());
            } else {

                $paginator = $nearst_partners;
            }

            $data = CustomPartnerResource::collection($paginator)->response()->getData(true);
            $msg = "fetch_nearst_partners";
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
