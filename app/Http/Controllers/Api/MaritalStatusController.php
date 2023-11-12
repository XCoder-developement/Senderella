<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\MaritalStatus\MaritalStatus;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class MaritalStatusController extends Controller
{
    use ApiTrait;
    public function fetch_marital_status(){
        $martial_statuses = MaritalStatus::get();
        $data=TitleResource::collection($martial_statuses);
        $msg="fetch_marital_status";
        return $this->dataResponse($msg ,$data ,200);
    }
}
