<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AppMessageReosurce;
use App\Models\AppMessage\AppMessage;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppMessageController extends Controller
{
    //
    use ApiTrait;

    public function send_app_message(Request $request)
    {
        try {
            $rules = [
                "name" => "required",
                "email" => "sometimes|email",
                "phone" => "sometimes",
                "subject" => "sometimes",
                "message" => "required",
            ];

            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->getvalidationErrors($validator); // Pass $validator instance
            }
            

            $data = [
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "subject" => $request->subject,
                "message" => $request->message,
            ];

            $app_message = AppMessage::create($data);

            $msg = __("messages.save successful");
            return $this->dataResponse($msg, new AppMessageReosurce($app_message), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
