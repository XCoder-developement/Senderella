<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Problem\Problem;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Validator;

class ProblemController extends Controller
{
    use ApiTrait;
    public function send_problem(Request $request)
    {
        try{
            $rules = [
                'email' => 'sometimes|email',
                'phone' => 'sometimes',
                'problem_type_id' => 'required|integer|exists:problem_types,id',
                'comment' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            // Manually check if either email or phone is provided
            if (empty($request->email) && empty($request->phone)) {
                return $this->successResponse(__('messages.enter_email_or_phone_at_least'),200);
            }

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator, 422);
            }

        $data['email']= $request->email;
        $data['problem_type_id']= $request->problem_type_id;
        $data['comment']= $request->comment;
        $data['phone']  = $request->phone;

        $problem = Problem::create($data);

        $msg = __("messages.save successful");

        return $this->successResponse($msg,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
