<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About\About;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    use ApiTrait;

    public function fetch_about(){
        try{


            $about = About::firstOrNew();

            //response
            $data =  $about->text ?? '';

            $msg = "fetch_about";

               return $this->dataResponse($msg, $data,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }
}
