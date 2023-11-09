<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Enums\VerificationTypeEnum;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;

class PhoneController extends Controller
{
    use ApiTrait;
    public function check_phone(Request $request)
    {
        try {

            //     validation
            $rules = [
                "phone" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = User::wherePhone($request->phone)->first();
            if (!$user) {
                $msg = __('messages.Sorry, this user does not exist');
                return $this->errorResponse($msg, 401);
            }
            //check if user exists
            elseif ($user) {
                $msg = __('messages.The user was found successfully');
                return $this->successResponse($msg, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
