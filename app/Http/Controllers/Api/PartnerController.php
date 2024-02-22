<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\FullPartnerResource;
use App\Models\Like\Like;
use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $type = 2;
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

                $type = 6;
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
                "reason" => "nullable",
                //reason_id
                "reason_ids" => "sometimes|array",
                "reason_ids.*" => "sometimes|exists:block_reasons,id",
            ];



            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }

            $user_id = auth()->id();
            $partner_id = $request->partner_id;
            $reason_ids = $request->reason_ids;
            $reason = $request->reason;

            $like_partner = UserBlock::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->first();

            if (!$like_partner) {
                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                $data['text'] =  $reason ?? null;
                $user_block = UserBlock::create($data);

                $user_block->reasons()->attach($reason_ids);

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
                $msg = __('messages.you already bookmarked this partner');
                return $this->errorResponse($msg, 200);
            } else {

                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                UserBookmark::create($data);

                $partner = User::whereId($partner_id)->first();
                //
                $user = auth()->user();
                $userId = $user->id;
                $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';
                $type = 5;
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


            $liked_before = UserWatch::where('user_id', $user_id)->where('partner_id', $partner_id)->first();
            if ($liked_before) {
                $liked_before->delete();
            }


            $type = 1;

            $data['user_id'] =  $user->id;
            $data['partner_id'] =  $partner_id;
            UserWatch::create($data);
            $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';

            $partner = User::whereId($partner_id)->first();
            $partner_devices = UserDevice::where('user_id', $partner->id)->pluck('device_token');
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

    public function who_favorite_me()
    {

        try {
            $user = auth()->user();
            $favorite_ids = $user->favorited_by()->orderBy('created_at', 'desc')->pluck("user_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();

            $favorites = implode(',', $favorite_ids); // Convert array to comma-separated string


            $favorite = User::select('users.*')
                ->join('user_bookmarks', 'users.id', '=', 'user_bookmarks.user_id')
                ->where('gender', '!=', $user->gender)
                ->whereIn('users.id', $favorite_ids)
                ->orderByRaw("FIELD(users.id, $favorites)") // Order by the sequence of IDs in the $followingUserIds array
                // ->orderBy('user_bookmarks.created_at', 'desc') // Then order by user_likes.created_at
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
            // dd(Carbon::parse($user->birthday_date)->subYears(5));
            $mdate = Carbon::parse($user->birthday_date)->subYears(5)->format('Y-m-d');
            $fdate = Carbon::parse($user->birthday_date)->addYears(5)->format('Y-m-d');
            // dd($date, $fdate);
            if ($user->gender == 1) { // if it's male it will show the female that compitable with him
                $compatible_partner = User::where('gender', 2)->where('height', '<=', $user->height)
                    // ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '>=', $mdate)
                    ->whereDate('birthday_date', '<=', $user->birthday_date);
            } else {
                $compatible_partner = User::where('gender', 1)->where('height', '>=', $user->height)
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
            $active_partner_counts = UserLike::groupBy('partner_id')
                ->select('partner_id', DB::raw('COUNT(partner_id) as count'))
                ->whereIn('user_id', function ($query) {
                    $query->select('user_id')->from('user_last_shows')->where('status', 1);
                })
                ->get()
                ->pluck('count', 'partner_id')->toArray();

            $disactive_partner_counts = UserLike::groupBy('partner_id')
                ->select('partner_id', DB::raw('COUNT(partner_id) as count'))
                ->whereIn('user_id', function ($query) {
                    $query->select('user_id')->from('user_last_shows')->where('status', 0)->orderBy('end_date', 'desc');
                })
                ->get()
                ->pluck('count', 'partner_id')->toArray();
            // dd($disactive_partner_counts->toArray());

            // $most_active_partner = $active_partner_counts->sortDesc()->keys()->toArray();
            // $most_disactive_partner = $disactive_partner_counts->sortDesc()->keys()->toArray();
            $mostLikedPartnerId = array_merge($active_partner_counts, $disactive_partner_counts);
            $mostLikedCount = User::whereIn('id', array_keys($mostLikedPartnerId))->where('gender', '!=', $user->gender);

            // $page = $request->page; // Set the page number
            // $perPage = 10; // Set the number of items per page
            // $offset = ($page - 1) * $perPage;

            // $mostLikedCount = $mostLikedCount->slice($offset, $perPage);
            $mostLikedCount = $mostLikedCount->paginate(10);

            if ($banner1 || $text_banner) {

                $combinedData = [];
                foreach ($mostLikedCount as $key => $partner) {
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
                    $mostLikedCount->total(),
                    $mostLikedCount->perPage(),
                    $mostLikedCount->currentPage(),
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $paginator->appends(request()->all());
            } else {

                $paginator = $mostLikedCount;
            }
            $msg = "fetch_most_liked_partners";
            $data = CustomPartnerResource::collection($paginator)->response()->getData(true);

            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function fetch_nearst_partners(Request $request)
    {

        try {
            $rules = [
                "longitude" => "required",
                "latitude" => "required",
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

            $nearst_partners = User::whereIn('id', $nearst_partnersids)
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
