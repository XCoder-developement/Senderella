<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report\Report;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;

class ReportController extends Controller
{
    use ApiTrait;
    public function send_report(Request $request)
    {
        try{
        $rules = [
            "report_type_id" => "required|integer",
            "reason" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->getvalidationErrors($validator,422);
        }
        $data['report_type_id']= $request->report_type_id;
        $data['reason']= $request->comment;

         Report::create($data);

        $msg = __("messages.save successful");

        return $this->successResponse($msg,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
