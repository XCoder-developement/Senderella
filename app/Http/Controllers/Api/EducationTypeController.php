<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\EducationType\EducationType;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class EducationTypeController extends Controller
{
    use ApiTrait;
    public function fetch_education_types(){

    try {
    $education_types = EducationType::get();
    $data = TitleResource::collection($education_types);
    $msg ="fetch_education_types";
    return $this ->dataResponse ($msg , $data , 200);

}
catch (\Exception $ex) {

    return $this->returnException($ex->getMessage(), 500);
}
}

}

