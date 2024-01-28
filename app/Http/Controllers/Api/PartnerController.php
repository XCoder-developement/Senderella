<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\FullPartnerResource;
use App\Models\Like\Like;
use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MiniPartnerResource;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\PartnerResource;
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

            $partners = User::whereNot('id', auth()->id())->orderBy('id', 'desc')->paginate(10);
            if (!$partners) {
                $msg = "message.there is no partners";

                return $this->errorResponse($msg, 401);
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

            $new_partners = User::orderBy('id', 'desc')->paginate(10);
            $msg = "fetch_new_partners";
            return $this->dataResponse($msg, PartnerResource::collection($new_partners)->response()->getData(true), 200);
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

            $user_id = auth()->id();
            $partner_id = $request->partner_id;

            $like_partner = UserLike::where([['user_id', '=', $user_id], ['partner_id', '=', $partner_id]])->first();

            $partner = User::whereId($partner_id)->first();
            if (!$like_partner) {
                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;

                $partner->update(['is_like_shown' => $partner->is_like_shown + 1]);
                $partner->update(['is_notification_shown' => $partner->is_notification_shown + 1]);

                SendNotification::send($partner->device_token ?? "", __('messages.new like'), __('messages.new like'));
                UserNotification::create([
                    'user_id' => $partner->id,
                    'title' => __('messages.new like'),
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
                //responce
                $partner->update(['is_bookmark_shown' => $partner->is_bookmark_shown + 1]);
                // SendNotification::send($partner->device_token ?? "", __('messages.bookmarked_by_user'), __('messages.bookmarked_by_user'));
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




            $data['user_id'] =  $user_id;
            $data['partner_id'] =  $partner_id;
            UserWatch::create($data);

            $partner = User::whereId($partner_id)->first();

            if ($partner->id != $user_id) {
                $partner->update(['is_watch_shown' => $partner->is_watch_shown + 1]);
                $partner->update(['is_notification_shown' => $partner->is_notification_shown + 1]);
                SendNotification::send($partner->device_token ?? "", __('messages.someone_viewed'), __("messages.someone_viewed"));
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

            $users = User::whereIn('id', $following_ids)->get();

            $msg = "fetch_following";
            return $this->dataResponse($msg, PartnerResource::collection($users), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_followers()
    {
        try {
            $user = auth()->user();
            $followers_ids = $user->followers->pluck('user_id')->toArray();
            $followers = User::whereIn('id', $followers_ids)->get();

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
            $watched_ids = $user->watched->pluck("partner_id")->toArray();
            $watched = User::whereIn('id', $watched_ids)->get();
            // dd($watched);
            $msg = "who_i_watch";
            return $this->dataResponse($msg, PartnerResource::collection($watched), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }



    public function who_watch_my_account()
    {
        try {
            $user = auth()->user();
            $watcher_ids = $user->watcher->where('partner_id', '!=', $user->id)->pluck("user_id")->toArray();
            $watcher = User::whereIn('id', $watcher_ids)->get();
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
            $favorited_ids = $user->favorited->pluck("partner_id")->toArray();
            $favorited = User::whereIn('id', $favorited_ids)->get();
            $msg = "who_i_favorite";
            return $this->dataResponse($msg, PartnerResource::collection($favorited), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function who_favorite_me()
    {

        try {
            $user = auth()->user();
            $favorite_ids = $user->favorited_by->pluck("user_id")->toArray();
            $favorite = UserWatch::whereIn('id', $favorite_ids)->get();
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

            if ($user->gender == 1) { // if it's male it will show the female that compitable with him
                $compatible_partner = User::where('gender', 2)->where('height', '<=', $user->height)
                    ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '>=', $user->birthday_date)
                    ->whereNot('id', $user->id)
                    ->get();
            } else {
                $compatible_partner = User::where('gender', 1)->where('height', '>=', $user->height)
                    ->where('weight', '<=', $user->weight + 10)->where('weight', '>=', $user->weight - 10)
                    ->where('country_id', $user->country_id)
                    ->where('state_id', $user->state_id)->where('marital_status_id', $user->marital_status_id)
                    ->where('marriage_readiness_id', $user->marriage_readiness_id)->where('color_id', $user->color_id)
                    ->where('education_type_id', $user->education_type_id)->where('is_married_before', $user->is_married_before)
                    ->whereDate('birthday_date', '<=', $user->birthday_date)->whereNot('id', $user->id)
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


            $partnerCounts = UserLike::groupBy('partner_id')
                ->select('partner_id', DB::raw('COUNT(partner_id) as count'))
                ->pluck('count', 'partner_id');


            $mostLikedPartnerId = $partnerCounts->sortDesc()->keys()->toArray();


            // Get the count for the most liked partner_id
            $mostLikedCount = User::whereIn('id', $mostLikedPartnerId)->get();

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
            
            $user = auth()->user();
            $partner = User::where('id', '!=', $user->id)->get();
            $nearst_partners = $partner->where('state_id', $user->state_id);
            $data = PartnerResource::collection($nearst_partners);
            $msg = "fetch_nearst_partners";
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
