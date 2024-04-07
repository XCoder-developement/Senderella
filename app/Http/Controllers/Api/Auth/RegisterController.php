<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\RegisterResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User\DeltedUser;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends Controller
{
    use ApiTrait;
    public function register(Request $request)
    {
        try {

            $rules = [
                "phone" => "required|unique:users,phone",
                "email" => "sometimes|unique:users,email",
                'password' => 'required|min:8',
                // "verification_type" => "required|integer",

            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {

                return $this->getvalidationErrors($validator);
            }

            $data["phone"] = $request->phone;
            $data["email"] = $request->email;
            $data['verification_code'] = rand(9999, 999999);
            $data["password"] = Hash::make($request->password);
            $data["api_token"] = Hash::make(rand(100, 55415415415));
            // $data["verification_type"] = $request->verification_type;

            //create new user
            $blocked_user = DeltedUser::where('phone' , $request->phone)->first();
            if ($blocked_user){
            if($blocked_user->trusted == 2){
                return $this->dataResponse(__('messages.this_phone_is_blocked'),  200);

            }
        }
            $user = User::create($data);


            //add device to user

            $user->user_device()->firstOrCreate([
                'device_token' => $request->device_token,
                'device_type' => $request->device_type,
                'device_id' => $request->device_id,

            ]);
            //response

            $msg = __("messages.save successful");

            return $this->dataResponse($msg, new RegisterResource($user), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function token_invalid()
    {
        //response
        $msg = __("messages.please login");
        return $this->errorResponse($msg, 401);
    }
}
