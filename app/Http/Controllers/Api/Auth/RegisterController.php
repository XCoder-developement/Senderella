<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends Controller
{
    use ApiTrait;
    public function register(Request $request){
     try {

         $rules = [
            "name" => "required",
            "phone" => "required|unique:users,phone",
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->getvalidationErrors($validator);

        }

        $data["name"] = $request->name;
        $data["phone"] = $request->phone;
        $data["invitation_code"] = rand(99999,999999);
        $data["password"] = Hash::make($request->password);
        $data["api_token"] = Hash::make(rand(100,55415415415));

        //create new user

        $user = User::create($data);


        //add device to user

        $user->user_device()->firstOrCreate([
            'device_token' => $request->device_token,
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,

        ]);
          //response

          $msg = __("messages.save successful");

          return $this->dataResponse($msg, new UserResource($user), 200);

 } catch (\Exception$ex) {
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
