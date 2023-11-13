<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CountryResource;
use App\Models\Location\Country\Country;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use ApiTrait;
    public function fetch_countries()
    {
        try {

            $countries = Country::get();
            //response

            $msg = "fetch_questions";

            return $this->dataResponse($msg,  CountryResource::collection($countries), 200);
        } catch (\Exception $ex) {

            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
