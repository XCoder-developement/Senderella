<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\SettingResource;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use ApiTrait;
    public function fetch_about()
    {
        try {
            $setting = Setting::firstOrNew();
            $re = new SettingResource($setting);
            $msg = "fetch_settings";
            return $this->dataResponse($msg, $re, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }
}
