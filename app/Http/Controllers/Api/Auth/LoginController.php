<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginController extends Controller
{
    use ApiTrait;
    public function login(Request $request){
        try {

            $rules = [
               "phone" => "required",
               'password' => 'required'
               ];

           $validator = Validator::make($request->all(), $rules);

           if ($validator->fails()) {

               return $this->getvalidationErrors($validator);

           }

           $user = User::wherePhone($request->phone)->first();

           //check if user exists

           if (!$user) {
            $msg = __('messages.Sorry, this user does not exist');
            return $this->errorResponse($msg, 200);
           }


           if (auth()->attempt($request->only(['phone', 'password']))) {


            //update api_token  user

               $user->update([
                "api_token" => Hash::make(rand(99,99999999))
               ]);

         //add device to user

          $user->user_devices()->firstOrCreate([
            'device_token' => $request->device_token,
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,

        ]);

               //response

               $msg = __('messages.Logged in successfully');
               return $this->dataResponse($msg, new UserResource($user));
           }
            else {
                $msg = __('messages.The password is wrong');
               return $this->errorResponse($msg, 401);
           }


        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
    public function logout(Request $request){
        try {
            $rules = [
                'device_token' => 'sometimes'
                ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {

                return $this->getvalidationErrors($validator);

            }
            $user = auth()->user();

            $user->update(["api_token" => null]);

            if($request->device_token){
                $user->user_device()->whereDeviceToken($request->device_token)->updateOrCreate([],[
                'device_token' => null
                ]);
            }else{
                $user->user_device()->updateOrCreate([],[
                    'device_token' => null
                    ]);
            }




            //response

            $msg = __('messages.Signed out successfully');
            return $this->dataResponse($msg, new UserResource($user));

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
