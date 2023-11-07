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
            "email" => "required|email",
            "problem_type_id" => "required|integer|exists:problem_types,id",
            "comment" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->getvalidationErrors($validator,422);
        }
        $data['email']= $request->email;
        $data['problem_type_id']= $request->problem_type_id;
        $data['comment']= $request->comment;

        $problem = Problem::create($data);

        $msg = __("messages.save successful");

        return $this->successResponse($msg,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
