<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\FullPartnerResource;
use App\Models\Like\Like;
use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MiniPartnerResource;
use App\Http\Resources\Api\NotificationPartnerResource;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\PartnerResource;
use App\Models\NewDuration\NewDuration;
use App\Models\User\UserBlock;
use App\Models\User\UserBookmark;
use App\Models\User\UserLike;
use App\Models\User\UserNotification;
use App\Models\User\UserWatch;
use App\Services\SendNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    use ApiTrait;
    public function fetch_all_partners()
    {
        try {

            $user = auth()->user();

            $active_partners = User::whereNot('id', auth()->id())->orderBy('id', 'desc')
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })->get();

            $disactive_partners = User::whereNot('id', auth()->id())
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->get();
            // dd($partners);
            $disactive_partners = $disactive_partners->sortByDesc(function ($partner) {
                return $partner->last_shows->first()->end_date ?? null;
            });

            $partners = $active_partners->merge($disactive_partners);
            if (!$partners) {
                $msg = "message.there is no partners";

                return $this->dataResponse($msg, 200);
            }
            $msg = "fetch_all_users";

            return $this->dataResponse($msg, PartnerResource::collection($partners)->response()->getData(true), 200);
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

            $user = auth()->user();
            $duration = NewDuration::first()->new_duration; // getting the duration days for the new tag
            // dd(Carbon::now()->subDays($duration)->format('Y-m-d h:m'));
            $actve_new_partners = User::where('id', '!=', $user->id)
                ->whereDate('created_at', '>', Carbon::now()->subDays($duration)->format('Y-m-d'))
                // ->orderBy('id', 'desc')
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->get();

            $off_new_partners = User::where('id', '!=', $user->id)->whereDate('created_at', '>', Carbon::now()->subDays($duration))
                ->whereDate('created_at', '>', Carbon::now()->subDays($duration)->format('Y-m-d'))
                // ->orderBy('id', 'desc')
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->get();

            $off_new_partners = $off_new_partners->sortByDesc(function ($partner) {
                return $partner->last_shows->first()->end_date ?? null;
            });

            $combinedPartners = $actve_new_partners->concat($off_new_partners);
            // dd($combinedPartners);
            $msg = "fetch_new_partners";
            return $this->dataResponse($msg, PartnerResource::collection($combinedPartners), 200);
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
                SendNotification::send($partner->user_device->device_token, __('messages.new_like'), __('messages.new_like'), $type, $userId, url($image) ?? '');
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
                SendNotification::send($partner->user_device->device_token, __('messages.bookmarked_by_user'), __('messages.bookmarked_by_user'), $type, $userId, url($image) ?? '');
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

            $user_id = auth()->id();
            $partner_id = $request->partner_id;


            $liked_before = UserWatch::where('user_id', $user_id)->where('partner_id', $partner_id)->first();
            if ($liked_before) {
                $liked_before->delete();
            }


            $type = 1;

            $user = auth()->user();
            $data['user_id'] =  $user->id;
            $data['partner_id'] =  $partner_id;
            UserWatch::create($data);
            $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';

            $partner = User::whereId($partner_id)->first();
            if ($partner->id != $user_id) {
                $userId = $partner->id;
                $partner->update(['is_watch_shown' => $partner->is_watch_shown + 1]);
                $partner->update(['is_notification_shown' => $partner->is_notification_shown + 1]);
                SendNotification::send($partner->user_device->device_token, __('messages.someone_viewed'), __("messages.someone_viewed"), $type, $userId, url($image) ?? '');
                UserNotification::create([
                    'user_id' => $partner->id,
                    'title' => __('messages.someone_viewed'),

                ]);
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
            $following_ids = $user->following->pluck('partner_id')->toArray();

            $users = User::join('user_likes', 'users.id', '=', 'user_likes.partner_id')
                ->whereIn('users.id', $following_ids)
                ->orderBy('user_likes.created_at', 'desc')
                ->select('users.*')
                ->distinct()
                ->get();


            $msg = "fetch_following";
            return $this->dataResponse($msg, NotificationPartnerResource::collection($users), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_followers()
    {
        try {
            $user = auth()->user();
            $followers_ids = $user->followers->pluck('user_id')->toArray();
            $followers = User::join('user_likes', 'users.id', '=', 'user_likes.user_id')
                ->whereIn('users.id', $followers_ids)
                ->orderBy('user_likes.created_at', 'desc')
                ->select('users.*')
                ->distinct()
                ->get();
            $msg = "fetch_followers";
            return $this->dataResponse($msg, PartnerResource::collection($followers), 200);
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
            $watched_ids = $user->Watched->last()->pluck('partner_id')->reject(function ($partner_id) use ($user) {
                return $partner_id == $user->id;
            })->toArray();

            $watched = User::join('user_watches', 'users.id', '=', 'user_watches.partner_id')
                ->whereIn('users.id', $watched_ids)
                ->orderByDesc('user_watches.id')
                ->select('users.*')
                ->distinct()
                ->get();

            // dd($watched);
            $msg = "who_i_watch";
            return $this->dataResponse($msg, NotificationPartnerResource::collection($watched), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }



    public function who_watch_my_account()
    {
        try {
            $user = auth()->user();
            $watcher_ids = $user->watcher->where('partner_id', $user->id)->pluck("user_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();
            // $watcher = User::whereIn('id', $watcher_ids)->get();
            $watcher = User::join('user_watches', 'users.id', '=', 'user_watches.user_id')
                ->whereIn('users.id', $watcher_ids)
                ->orderBy('user_watches.created_at', 'desc')
                ->select('users.*')
                ->distinct()
                ->get();

            $msg = "who_watch_my_account";
            return $this->dataResponse($msg, PartnerResource::collection($watcher), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function who_i_favorite()
    {

        try {
            $user = auth()->user();
            $favorited_ids = $user->favorited->pluck("partner_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();

            $favorited = User::join('user_bookmarks', 'users.id', '=', 'user_bookmarks.partner_id')
                ->whereIn('users.id', $favorited_ids)
                ->orderBy('user_bookmarks.created_at', 'desc')
                ->select('users.*')
                ->distinct()
                ->get();

            $msg = "who_i_favorite";
            return $this->dataResponse($msg, NotificationPartnerResource::collection($favorited), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function who_favorite_me()
    {

        try {
            $user = auth()->user();
            $favorite_ids = $user->favorited_by()->pluck("user_id")
                ->reject(function ($user_id) use ($user) {
                    return $user_id == $user->id;
                })->toArray();
            $favorite = User::join('user_bookmarks', 'users.id', '=', 'user_bookmarks.user_id')
                ->whereIn('users.id', $favorite_ids)
                ->orderBy('user_bookmarks.created_at', 'desc')
                ->select('users.*')
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
                    ->whereDate('birthday_date', '<=', $user->birthday_date)
                    ->get();
            } else {
                $compatible_partner = User::where('gender', 1)->where('height', '>=', $user->height)
                    // ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '<=', $fdate)->whereDate('birthday_date', '>=', $user->birthday_date)
                    ->get();
            }
            $msg = "most_compatible_partners";
            // dd($compatible_partner);
            return $this->dataResponse($msg, PartnerResource::collection($compatible_partner), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_most_liked_partners()
    {
        try {


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
            $mostLikedCount = User::whereIn('id', array_keys($mostLikedPartnerId))->get();


            $msg = "fetch_most_liked_partners";
            $data = PartnerResource::collection($mostLikedCount);

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

            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distance = 5;    //* the redaius is about 5meter

            $distanceInDegrees = $distance / (111.32 * 1000);

            $user = auth()->user();
            $active_nearst_partners = User::where('id', '!=', $user->id)
                ->whereBetween('latitude', [$latitude - $distanceInDegrees, $latitude + $distanceInDegrees])
                ->whereBetween('longitude', [$longitude - $distanceInDegrees, $longitude + $distanceInDegrees])
                ->where('visibility', 0)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 1);
                })
                ->get();
                $disactive_nearst_partners = User::where('id', '!=', $user->id)
                ->whereBetween('latitude', [$latitude - $distanceInDegrees, $latitude + $distanceInDegrees])
                ->whereBetween('longitude', [$longitude - $distanceInDegrees, $longitude + $distanceInDegrees])
                ->where('visibility', 0)
                ->whereHas('last_shows', function ($query) {
                    $query->where('status', 0);
                })
                ->get();
                // dd($active_nearst_partners);

            $disactive_nearst_partners = $disactive_nearst_partners->sortByDesc(function ($partner) {
                return $partner->last_shows->first()->end_date ?? null;
            });

            $nearst_partners = $active_nearst_partners->merge($disactive_nearst_partners);

            $data = PartnerResource::collection($nearst_partners);
            $msg = "fetch_nearst_partners";
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
