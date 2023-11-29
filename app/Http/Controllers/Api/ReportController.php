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
        try {
            $rules = [
                "partner_id"=>"required|exists:users,id",
                "report_type_ids" => "required|array",
                "reason" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator);
            }


                $data['user_id'] =auth()->id();
                $data['reason'] =$request->reason;
                $data['partner_id'] = $request->partner_id;
                $report = Report::create($data);
                $report->report_types()->attach($request->report_type_ids);


            $msg = __("messages.save successful");

            return $this->successResponse($msg, 200);

        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

}}
