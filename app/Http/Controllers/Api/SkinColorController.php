<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\Color\Color;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class SkinColorController extends Controller
{
    //
    use ApiTrait;
    public function fetch_skin_colors(){
        try{

        $skin_color = Color::get();
        $data = TitleResource::collection($skin_color);
        $msg = "fetch_skin_colors";
        return $this ->dataResponse($msg , $data ,200);

    }
    catch (\Exception $ex) {

        return $this->returnException($ex->getMessage(), 500);
    }
    }
}
