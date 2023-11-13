<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Privacy\Privacy;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    use ApiTrait;

    public function fetch_privacy()
    {
        try {


            $privacy = Privacy::firstOrNew();

            //response
            $data =  $privacy->text ?? '';

            $msg = "fetch_privacy";

            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
