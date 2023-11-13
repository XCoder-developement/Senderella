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
    public function fetch_all_partners()
    {
        try {

            $user = auth()->user();

            $partners = User::whereNot('id', auth()->id())->orderBy('id', 'desc')->paginate(10);
            if (!$partners) {
                $msg = "there is no partners";

                return $this->errorResponse($msg, 401);
            }
            $msg = "fetch_all_users";

            return $this->dataResponse($msg, PartnerResource::collection($partners)->response()->getData(true), 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    
}
