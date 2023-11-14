<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Models\User\UserImage;
use App\Models\User\UserInformation;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// use Validator;

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
                "nationality_id" => "required|integer|exists:countries,id",
                "state_id" => "required|integer|exists:states,id",
                "marital_status_id" => "required|integer|exists:marital_statuses,id",
                "readiness_for_marriages_id" => "required|integer|exists:marriage_readinesses,id",
                "education_type_id" => "required|integer|exists:education_types,id",
                "skin_color_id" => "required|integer|exists:colors,id",
                "is_married_before" => "required|integer",
                "weight" => "required",
                "height" => "required",
                "notes" => "sometimes",
                "about_me" => "sometimes",
                "important_for_marriage" => "sometimes",
                "partner_specifications" => "sometimes",

                "user_information" => "sometimes|array",
                "user_information.*.requirment_id" => "sometimes|exists:requirments,id",
                "user_information.*.requirment_item_id" => "sometimes|exists:requirment_items,id",

                "questions" => "sometimes|array",
                "questions.*.requirment_id" => "sometimes|exists:requirments,id",
                "questions.*.requirment_item_id" => "sometimes|exists:requirment_items,id",
                "questions.*.answer" => "sometimes",

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
            $data['is_married_before'] = $request->is_married_before;
            $data['weight'] = $request->weight;
            $data['height'] = $request->height;
            $data['notes'] = $request->notes;
            $data['marital_status_id'] = $request->marital_status_id;
            $data['marriage_readiness_id'] = $request->readiness_for_marriages_id;
            $data['color_id'] = $request->skin_color_id;
            $data['education_type_id'] = $request->education_type_id;

            $data['about_me'] = $request->about_me;
            $data['important_for_marriage'] = $request->important_for_marriage;
            $data['partner_specifications'] = $request->partner_specifications;

            $user->update($data);

            if ($request->user_information) {
                foreach ($request->user_information as $user_information) {
                    $requirment_id = $user_information["requirment_id"];
                    $requirment_item_id = $user_information["requirment_item_id"];

                    $user_info_data['requirment_id'] = $requirment_id;
                    $user_info_data['requirment_item_id'] = $requirment_item_id;
                    $user_info_data['user_id'] = $user->id;

                    UserInformation::create($user_info_data);
                }
            }
            if ($request->questions) {
                foreach ($request->questions as $question) {
                    $requirment_id = $question["requirment_id"];
                    $requirment_item_id = $question["requirment_item_id"];
                    $answer = $question["answer"];

                    $user_info_data['requirment_id'] = $requirment_id;
                    $user_info_data['requirment_item_id'] = $requirment_item_id;
                    $user_info_data['answer'] = $answer;
                    $user_info_data['user_id'] = $user->id;

                    UserInformation::create($user_info_data);
                }
            }
            $msg = __("messages.save successful");

            return $this->dataResponse($msg, new UserResource($user), 200);
        } catch (\Exception $ex) {
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
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
