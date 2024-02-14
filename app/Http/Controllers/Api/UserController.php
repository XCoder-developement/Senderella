<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User\DeltedUser;
use App\Models\User\User;
use App\Models\User\UserBookmark;
use App\Models\User\UserDevice;
use App\Models\User\UserDocument;
use App\Models\User\UserImage;
use App\Models\User\UserInformation;
use App\Models\User\UserLastShow;
use App\Models\User\UserLike;
use App\Models\User\UserNotification;
use App\Models\User\UserWatch;
use App\Traits\ApiTrait;
use Carbon\Carbon;
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
        $p = 13;
        try {
            //validation
            $rules = [
                "name" => "required|max:12",
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
                "is_married_before" => "sometimes|integer",
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
                "questions.*.question_id" => "sometimes|exists:requirments,id",
                // "questions.*.requirment_item_id" => "sometimes|exists:requirment_items,id",
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
            $data['notes'] = $request->notes ;
            $data['marital_status_id'] = $request->marital_status_id;
            $data['marriage_readiness_id'] = $request->readiness_for_marriages_id;
            $data['color_id'] = $request->skin_color_id;
            $data['education_type_id'] = $request->education_type_id;

            $data['about_me'] = $request->about_me ;
            $data['important_for_marriage'] = $request->important_for_marriage ;
            $data['partner_specifications'] = $request->partner_specifications ;
            $data['percentage'] = intval(($p / 20) * 100);
            if ($request->notes) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 20) * 100);
            }
            if ($request->about_me) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 20) * 100);
            }
            if ($request->is_married_before) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 20) * 100);
            }
            if ($request->important_for_marriage) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 20) * 100);
            }
            if ($request->partner_specifications) {
                $p++;
                $data['percentage'] = intval((($p + 1) / 20) * 100);
            }
            // if ($request->partner_specifications) {
            //     $p++;
            //     $data['percentage'] = intval((($p + 1) / 20) * 100);
            // }else{
            //     $data[$request->partner_specifications] = 'Not answered';
            // }

            $user->update($data);

            if ($request->user_information) {
                foreach ($request->user_information as $user_information) {
                    $requirment_id = $user_information["requirment_id"];
                    $requirment_item_id = $user_information["requirment_item_id"];
                    // Check if a record with the same requirment_id exists
                    $existingRecord = UserInformation::where('requirment_id', $requirment_id)
                        ->where('user_id', $user->id)
                        ->where('type', 1)
                        ->first();

                        if($user_information["requirment_item_id"]){
                    $requirment_item_id = $user_information["requirment_item_id"];
                        }else{
                            $requirment_item_id = 'message.not_answered';
                        }

                    if ($existingRecord) {
                        // If the record exists, update the existing record
                        $existingRecord->update([
                            'requirment_item_id' => $requirment_item_id,
                        ]);
                    } else {
                        // If the record does not exist, create a new record
                        $user_info_data = [
                            'requirment_id' => $requirment_id,
                            'requirment_item_id' => $requirment_item_id,
                            'user_id' => $user->id,
                            'type' => 1,
                        ];

                        UserInformation::create($user_info_data);
                    }
                }
            }

            if ($request->questions) {
                    foreach ($request->questions as $question) {
                        $requirment_id = $question["question_id"];
                        $answer = $question["answer"];

                        // Check if a record with the same requirment_id exists
                        $existingRecord = UserInformation::where('requirment_id', $requirment_id)
                            ->where('user_id', $user->id)
                            ->where('type', 2)
                            ->first();

                        if ($question["answer"]){
                            $answer = $question["answer"];
                        }else{
                            $answer = 'message.not_answered';
                        }

                        if ($existingRecord) {
                            // If the record exists, update the answer
                            $existingRecord->update(['answer' => $answer]);
                        } else {
                            // If the record does not exist, create a new record
                            $user_info_data = [
                                'requirment_id' => $requirment_id,
                                'answer' => $answer,
                                'user_id' => $user->id,
                                'type' => 2,
                            ];

                            UserInformation::create($user_info_data);
                        }
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

                // $user->update(['is_verify' => 1]);
            }

            $msg = __("messages.account_document_succseed");

            return $this->successResponse($msg, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function delete_account()
    {
        try {

            $user = auth()->user();

            if ($user->api_token) { // check the api_token ig gotten right?
                // storing the user data in delted users table
                // dd($user->images?->where('is_primary', 1)->first()->image_link ?? '');
                $delted = DeltedUser::create([
                    'name'    => $user->name,
                    'email'   => $user->email,
                    'phone'   => $user->phone,
                    'birthday_date' => $user->birthday_date,
                    'country_id'    => $user->country_id,
                    'nationality_id'    => $user->nationality_id,
                    'state_id'      => $user->state_id,
                    'marital_status_id' => $user->marital_status_id,
                    'marriage_readiness_id' => $user->marriage_readiness_id,
                    'color_id'      => $user->color_id,
                    'education_type_id' => $user->education_type_id,
                    'weight'        => $user->weight,
                    'height'        => $user->height,
                    'trusted'       => $user->trusted,
                    'is_verify'     => $user->is_verify,
                    'notes'         => $user->notes,
                    'about_me'      => $user->about_me,
                    'important_for_marriage' => $user->important_for_marriage,
                    'is_married_before' => $user->is_married_before,
                    'partner_specifications' => $user->partner_specifications,
                    'gender'        => $user->gender,
                    'image'         => $user->images?->where('is_primary', 1)->first()->image_link ?? '',
                ]);
                // $delted->image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';


                // delte the user data
                UserImage::where('user_id', $user->id)->delete();
                UserLike::where('user_id', $user->id)->delete();
                UserLike::where('partner_id', $user->id)->delete();
                UserWatch::where('user_id', $user->id)->delete();
                UserBookmark::where('user_id', $user->id)->delete();
                UserLastShow::where('user_id', $user->id)->delete();
                UserNotification::where('user_id', $user->id)->delete();
                UserDocument::where('user_id', $user->id)->delete();
                UserDevice::where('user_id', $user->id)->delete();

                User::destroy('id', $user->id);




                $msg = __('message.account is delted successfully');
                return $this->successResponse($msg, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function entry_status(Request $request)
    {
        try {
            $rules = [
                "status" => "required|integer",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }

            $entry_status = UserLastShow::whereUserId(auth()->id())->first();
            if(!$entry_status){

                $data['status'] = $request->status;
                if($request->status == 1){
                    $data['start_date'] = Carbon::now();
                    $data['end_date'] = null;
                }elseif($request->status == 0){
                    $data['start_date'] = null;
                    $data['end_date'] = Carbon::now();
                }
                $data['user_id'] = auth()->id();
                UserLastShow::create($data);
                auth()->user()->update(['active' =>  $request->status]);

            }elseif($entry_status){

                $data['status'] = $request->status;
                if($request->status == 1){
                    $data['start_date'] = Carbon::now();
                    $data['end_date'] = null;
                }elseif($request->status == 0){
                    $data['start_date'] = $entry_status->start_date ?? null;
                    $data['end_date'] = Carbon::now();
                }

                $entry_status->update($data);
                auth()->user()->update(['active' => $request->status]);

            }

            $msg = __('message.status updated successfully');
                return $this->successResponse($msg, 200);

        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function new_partner_activity()
    {
        try {

            $user = auth()->user();
            $data['is_like_shown'] = intval($user->is_like_shown) ?? '';
            $data['is_notification_shown'] = intval($user->is_notification_shown) ?? '';
            $data['is_post_shown'] = intval($user->is_post_shown) ?? '';
            $data['is_bookmark_shown'] = intval($user->is_bookmark_shown) ?? '';
            $data['is_watch_shown'] = intval($user->is_watch_shown) ?? '';
            $data['active'] = intval($user->active) ?? '';
            $data['last_active'] = $user->last_shows !== null && $user->last_shows->first() ? $user->last_shows?->first()?->end_date : 'active now';

            $user->update([
                'is_like_shown' => 0,
                'is_notification_shown' => 0,
                'is_post_shown' => 0,
                'is_bookmark_shown' => 0,
                'is_watch_shown' => 0,
            ]);
            $msg = __('message.new_partner_activity');
            return $this->dataResponse($msg,$data, 200);

        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function set_visibility(Request $request){
        try{
            $rules = [
                'visibility' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }
            $user = auth()->user();
            $user->update(['visibility' => $request->visibility]);

            $data = new UserResource($user);
            $msg = __('message.done_updating_visibility');
            return $this->dataResponse($msg,$data, 200);

            }
        catch (\Exception $ex){
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function update_location(Request $request){
        try{
            $rules = [
                'latitude' => 'required',
                'longitude' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return $this->getvalidationErrors($validator);
            }

            $user = auth()->user();
            $user->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);

            $data = new UserResource($user);
            $msg = __('message.done_updating_location');
            return $this->dataResponse($msg,$data, 200);
        }

        catch (\Exception $ex){
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function users_off(){
        try{
            $rest_users = UserLastShow::pluck('user_id')->toArray();

            $users = User::whereNotIn('id', $rest_users)->pluck('id')->toArray();

            foreach($users as $user){

                    $user->last_shows()->create(['user_id' => $user,'status' => 0 , 'end_date' => Carbon::now()->subMonths(2)->addSeconds(rand(0, 5184000))]);

            }

            $msg = __('message.users_off');
            return $this->dataResponse($msg,$users, 200);
        }
        catch (\Exception $ex){
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
