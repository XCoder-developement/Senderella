<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class PasswordController extends Controller
{
    use ApiTrait;
    public function change_password(Request $request)
    {
        try {

            //validation

            $rules = [
                "old_password" => "required|min:8",
                "new_password" => "required|min:8",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();

            //check if old password and new password if the same

            if (Hash::check($request->old_password, auth()->user()->password)) {

                $user->update([

                    'password' => hash_user_password($request->new_password),
                ]);

                //response

                $msg = __('messages.Password has been changed successfully');


                return $this->dataResponse($msg, new UserResource($user),200);

            } else {

                $msg = __('messages.The old password does not match the user password');

                return $this->errorResponse($msg, 401);
            }
        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
    public function set_password(Request $request)
    {
        try {

            //validation

            $rules = [
                "phone" => "required",
                "password" => "required|min:8",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {

                return $this->getvalidationErrors($validator);

            }

            $user = User::wherePhone($request->phone)->first();


            //check if user exist

            if (!$user) {
                $msg = __('messages.Sorry, this user does not exist');
                return $this->errorResponse($msg, 200);
            }
                $user->update([
                    'password' => hash_user_password($request->password),
                ]);

                //response

                $msg = __('messages.Password has been changed successfully');
                return $this->dataResponse($msg, new UserResource($user), 200);


        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
