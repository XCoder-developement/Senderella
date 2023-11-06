<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Models\User\UserImage;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    use ApiTrait;
    public function set_user_data(Request $request)
    {
        try {
            //validation
            $rules = [
                "name" => "required",
                "phone" => "required",
                "email" => "required",
                "gender" => "required|integer",
                "birthday_date" => "required|date",
                "country_id" => "required|integer|exists:countries,id",
                "state_id" => "required|integer|exists:states,id",
                "nationality_id" => "required",
                "marital_status" => "required|integer",
                "is_married_before" => "required|integer",
                "readiness_for_marriage" => "required",
                "weight" => "required",
                "height" => "required",
                "notes" => "required",
                // "verification_type" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();
            $data['name'] = $request->name;
            $data['phone'] = $request->phone;
            $data['email'] = $request->email;
            $data['gender'] = $request->gender;

            $data['birthday_date'] = $request->birthday_date;
            $data['country_id'] = $request->country_id;
            $data['state_id'] = $request->state_id;
            $data['nationality_id'] = $request->nationality_id;
            $data['marital_status'] = $request->marital_status;
            $data['is_married_before'] = $request->is_married_before;
            $data['readiness_for_marriage'] = $request->readiness_for_marriage;
            $data['weight'] = $request->weight;
            $data['height'] = $request->height;
            $data['notes'] = $request->notes;

            $user->update($data);

            $msg = __("messages.save successful");

          return $this->dataResponse($msg, new UserResource($user), 200);
        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function set_user_images(Request $request)
    {
        try {
            //validation
            $rules = [
                "images" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();

            if (count($request->images) > 0) {
                foreach ($request->images as $image) {
                    $image_data = upload_image($image, "users");
                    UserImage::create([
                        'image' => $image_data,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $msg = __("messages.save successful");

          return $this->dataResponse($msg, new UserResource($user), 200);
        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
