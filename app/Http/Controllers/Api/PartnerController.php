<?php

namespace App\Http\Controllers\Api;

use App\Models\Like\Like;
use App\Traits\ApiTrait;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\PartnerResource;
use App\Models\User\UserBlock;
use App\Models\User\UserLike;

class PartnerController extends Controller
{
    use ApiTrait;
    public function fetch_all_partners()
    {
        try {

            $user = auth()->user();

            $partners = User::whereNot('id', auth()->id())->orderBy('id', 'desc')->paginate(10);
            if (!$partners) {
                $msg = "there is no partners";

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
            return $this->dataResponse($msg, new PartnerResource($partner), 200);
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

            if ($like_partner) {
                $msg = __('messages.you already liked this partner');
                return $this->errorResponse($msg, 200);
            } else {

                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                UserLike::create($data);

                $partner = User::whereId($partner_id)->first();
                //responce
                $msg = "like_partner";
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

            if ($like_partner) {
                $msg = __('messages.you already blocked this partner');
                return $this->errorResponse($msg, 200);
            } else {

                $data['user_id'] =  $user_id;
                $data['partner_id'] =  $partner_id;
                $data['text'] =  $reason ?? null;
                $user_block = UserBlock::create($data);

                $user_block->reasons()->attach($reason_ids);

                $msg = __("messages.save successful");

                return $this->successResponse($msg, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    // public function fetch_following()
    // {
    //     try {
    //         $user = auth()->user();

    //         // Assuming 'likes' is the relationship for users being followed
    //         $following = UserLike::where('user_id',$user->id)->get();

    //         $msg = "fetch_following";
    //         return $this->dataResponse($msg, PartnerResource::collection($following), 200);
    //     } catch (\Exception $ex) {
    //         return $this->returnException($ex->getMessage(), 500);
    //     }
    // }
}
