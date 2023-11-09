<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PackageResource;
use App\Models\Package\Package;
use App\Models\User\UserPackage;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Validator;

class PackageController extends Controller
{
    use ApiTrait;

    public function fetch_packages()
    {
        try {

            $packages = Package::get();

            //response
            $data = PackageResource::collection($packages);

            $msg = "fetch_packages";
            return $this->dataResponse($msg, $data, 200);


        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function subscribe_package(Request $request)
    {
        try {

        $rules = [
            "package_id" => "required|integer|exists:packages,id",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->getvalidationErrors($validator,422);
        }
        $data['package_id'] = $request->package_id;
        $data['user_id']= auth()->id();

        UserPackage::create($data);

        $msg = __("messages.save successful");

        return $this->successResponse($msg,200);

        } catch (\Exception$ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
