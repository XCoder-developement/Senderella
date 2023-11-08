<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Term\Term;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class TermController extends Controller
{
    use ApiTrait;

    public function fetch_term(){
        try{


            $term = Term::firstOrNew();

            //response
            $data =  $term->text ?? '';

            $msg = "fetch_term";

            return $this->dataResponse($msg, $data,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }
}
