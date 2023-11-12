<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\MarriageReadiness\MarriageReadiness;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class MarriageReadinessController extends Controller
{
    use ApiTrait;
    public function fetch_readiness_for_marriages(){
        $readiness_marriage=MarriageReadiness::get();
        $data=TitleResource::collection($readiness_marriage);
        $msg="fetch_readiness_for_Marriages";
        return $this->dataResponse($msg, $data , 200);

    }
}
