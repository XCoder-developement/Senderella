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
                "report_type_id" => "required|array",
                "reason" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getvalidationErrors($validator, 422);
            }

            $reportsData = $request->input('reports', []); // Provide a default empty array if 'reports' is not present

            foreach ($reportsData as $reportData) {
                $data['report_type_id'] = $reportData['report_type_id'];
                $data['reason'] = $reportData['reason'];
                Report::create($data);
            }

            $msg = __("messages.save successful");

            return $this->successResponse($msg, 200);

        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

}}
