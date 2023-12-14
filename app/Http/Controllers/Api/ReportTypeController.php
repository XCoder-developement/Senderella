<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\ReportType\ReportType;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ReportTypeController extends Controller
{
    use ApiTrait;

    public function fetch_report_types()
    {
        try {


            $Report_types = ReportType::get();

            //response
            $data = TitleResource::collection($Report_types);
            $msg = "fetch_Report_types";


            return $this->dataResponse($msg, $data, 200);


        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
