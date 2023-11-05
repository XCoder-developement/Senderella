<?php

namespace App\Traits;

use App\Models\Teacher\Teacher;

trait ApiTrait
{

    public function errorResponse($msg, $code)
    {
        return response()->json([
            'status' => false,
            'message' => $msg,
        ], $code);
    }

    public function successResponse($msg, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $msg,
        ], $code);
    }

    public function dataResponse($msg, $data, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $msg,
            'data' => $data,
        ], $code);
    }

    public function getvalidationErrors($validator, $code = 422)
    {
        return $this->errorResponse($validator->errors()->first(), $code);
    }

    public function returnException($message, $code)
    {
        return $this->errorResponse($message, $code);
    }

    public function check_teacher_apikey($api_key)
    {


        $teacher = Teacher::whereApiKey($api_key)->first();

        return $teacher ? $teacher->id : null;
    }

    public function check_api_key($request)
    {

        $api_key = $request->header("api_key");
        if ($api_key == null) {
            return $this->errorResponse("api key is empty add it ", 400);
        }
        $teacher = Teacher::whereApiKey($api_key)->first();
        if ($teacher) {
            return $teacher->id;
        }

        return $this->errorResponse("api key is invaild", 400);
    }
}
