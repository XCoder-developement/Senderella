<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User\User;
use App\Models\User\UserDocument;
use App\Models\User\UserImage;
use App\Models\User\UserInformation;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\NotIn;

// use Validator;

class UserController extends Controller
{
    use ApiTrait;
    public function set_user_data(Request $request)
    {
        $p = 15;
        try {
            //validation
            $rules = [
                "name" => "required",
                // "phone" => "required",
                "email" => "sometimes",
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
            // $data['phone'] = $request->phone;
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
            $data['percentage'] = intval(($p / 21) * 100);
            if ($request->notes) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 21) * 100);
            }
            if ($request->about_me) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 21) * 100);
            }
            if ($request->important_for_marriage) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 21) * 100);
            }
            if ($request->partner_specifications) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 21) * 100);
            }
            if ($request->partner_specifications) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 21) * 100);
            }

            $user->update($data);

            if ($request->user_information) {
                foreach ($request->user_information as $user_information) {
                    $requirment_id = $user_information["requirment_id"];
                    $requirment_item_id = $user_information["requirment_item_id"];

                    $user_info_data['requirment_id'] = $requirment_id;
                    $user_info_data['requirment_item_id'] = $requirment_item_id;
                    $user_info_data['user_id'] = $user->id;
                    $user_info_data['type'] = 1;

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
                    $user_info_data['type'] = 2;

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
                "stored_images" => "sometimes|array",
                "stored_images.*.id" => "sometimes",
                "stored_images.*.is_primary" => "sometimes",
                "stored_images.*.is_blurry" => "sometimes",
                "imagesArray" => "sometimes|array",
                "imagesArray.*.image" => "sometimes",
                "imagesArray.*.is_primary" => "sometimes",
                "imagesArray.*.is_blurry" => "sometimes",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();
            // dd($request->all());
            // $imagesData = collect($request->get('images'))->map(function ($imageData) use ($user) {
            //     UserImage::create([
            //         'image' => $imageData['images'],
            //         'user_id' => $user->id,
            //         'is_primary' => $imageData['is_primary'],
            //         'is_blurry' => $imageData['is_blurry'],
            //     ]);
            // })->toArray();
            $user_images = $user->images()->pluck("id")->toArray();
            // dd($user_images);

            if ($request->stored_images == [] && $request->imagesArray == []) {
                $msg = __("message.error, you have to upload images");
                return $this->successResponse($msg, 200);
            } else {

                if ($request->stored_images != []) {
                    foreach ($request->stored_images as $img) {

                        if (in_array($img['id'], $user_images)) {
                            DB::table('user_images')
                                ->where('id', $img)
                                ->update([
                                    'is_primary' => $img['is_primary'],
                                    'is_blurry' => $img['is_blurry'],
                                ]);
                        }
                    }

                    $imagesToDelete = array_diff($user_images, array_column($request->stored_images, 'id'));

                    if (!empty($imagesToDelete)) {
                        DB::table('user_images')->whereIn('id', $imagesToDelete)->delete();
                    }
                }


                // if($user->images()){
                //     $user->images()->delete();
                // }

                // if ($user->images()) {
                //     foreach ($user->images as $index => $image) {
                //         $imageData = $request->imagesArray[$index] ?? null;

                //         if ($imageData) {
                //             // Update the image data without re-uploading
                //             DB::table('user_images')
                //                 ->where('id', $image->id)
                //                 ->update([
                //                     'is_primary' => $imageData['is_primary'],
                //                     'is_blurry' => $imageData['is_blurry'],
                //                 ]);
                //         }
                //     }
                // }

                // Change this condition to check the existence of "imagesArray"
                if ($request->has('imagesArray') && is_array($request->imagesArray)) {
                    foreach ($request->imagesArray as $user_image) {

                        // if (isset($user_image['image']) && is_uploaded_file($user_image['image'])) {
                        $image = upload_image($user_image['image'], "users");
                        $user_image_data['image'] = $image;
                        // }


                        $is_primary = $user_image['is_primary'];
                        $is_blurry = $user_image['is_blurry'];



                        $user_image_data['is_primary'] = $is_primary;
                        $user_image_data['is_blurry'] = $is_blurry;
                        $user_image_data['user_id'] = $user->id;

                        UserImage::create($user_image_data);
                    }
                }



                // UserImage::insert($imagesData);

                $msg = __("messages.save successful");

                return $this->dataResponse($msg, new UserResource($user), 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }



    public function account_document(Request $request)
    {
        try {
            //validation
            $rules = [
                "image" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();
            if (($request->image)) {
                $document_data = upload_image($request->image, "users");
                UserDocument::create([
                    'image' => $document_data,
                    'user_id' => $user->id,
                ]);

                $user->update(['is_verify' => 1]);
            }

            $msg = ("account_document_succseed");

            return $this->successResponse($msg, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function delte_account()
    {
        try {

            $user = auth()->user();

            if ($user->api_token) { // check the api_token ig gotten right?
                // delte the user data
                User::destroy('id', $user->id);

                $msg = 'account is delted successfully';
                return $this->successResponse($msg, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
