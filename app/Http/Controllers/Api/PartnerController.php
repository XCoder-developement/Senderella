<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use ApiTrait;
    public function fetch_all_partners(){
        $partners = User::get();
        $msg="fetch_all_users";
        return $this->dataResponse($msg , PartnerResource::collection($partners),200);

    }
}
