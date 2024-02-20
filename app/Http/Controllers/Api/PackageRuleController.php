<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackageRule\PackageRule;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PackageRuleController extends Controller
{
    //
    use ApiTrait;

    public function fetch_package_rules(){
        try{


            $package_rule = PackageRule::firstOrNew();

            //response
            $data =  $package_rule->text ?? '';

            $msg = __("message.fetch_package_rules");

            return $this->dataResponse($msg, $data,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }
}
