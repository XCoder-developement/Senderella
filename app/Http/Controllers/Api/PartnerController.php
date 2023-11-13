<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

            $partners =[
             "partner_id" => "required|exists:users,id",
            ];
            $validator = Validator::make(request()->all(),$partners);
            if ($validator->fails()) {
                return $this->getvalidationErrors("validator");
            }
            $partner= User::whereId($request->partner_id)->first();

            $msg="fetch_partner_details";
            return $this->dataResponse($msg, new PartnerResource($partner), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function new_partners()
    {
        try {

            $user = auth()->user();

            $new_partners = User::whereMonth('created_at', date('m'))->paginate(10);

            $msg = "fetch_new_partners";

            return $this->dataResponse($msg, PartnerResource::collection($new_partners)->response()->getData(true), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


}
