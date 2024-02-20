<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackageTerm\PackageTerm;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PackageTermController extends Controller
{
    //
    use ApiTrait;

    public function fetch_package_terms(){
        try{


            $package_term = PackageTerm::firstOrNew();

            //response
            $data =  $package_term->text ?? '';

            $msg = __("message.fetch_package_terms");

            return $this->dataResponse($msg, $data,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }
}
