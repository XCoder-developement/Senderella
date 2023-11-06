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
    public function check_phone_and_email(Request $request)
    {
        try {

            //validation

            $rules = [
                "phone" => "required",
                "verification_type" => "required|integer",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = User::wherePhone($request->phone)->first();

            //check if user exists

            if ($user) {
                $msg = __('messages.The user was found successfully');
                return true;
                if ($request->verification_type == VerificationTypeEnum::phone->value) {
                    $user->update([
                        'verification_type' => VerificationTypeEnum::phone->value
                    ]);
                }
                if ($request->verification_type == VerificationTypeEnum::email->value) {
                    $user->update([
                        'verification_type' => VerificationTypeEnum::email->value
                    ]);
                }
            } else {
                $msg = __('messages.Sorry, this user does not exist');
                return false;
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
