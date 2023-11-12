<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProblemTypeResource;
use App\Http\Resources\Api\TitleResource;
use App\Models\ProblemType\ProblemType;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ProblemTypeController extends Controller
{
    use ApiTrait;

    public function fetch_problem_types()
    {
        try {


            $problem_types = ProblemType::get();

            //response
            $data = TitleResource::collection($problem_types);
            $msg = "fetch_problem_types";


            return $this->dataResponse($msg, $data, 200);
            // return response()->json([
            //     'status' =>true,
            //     'message' => $msg,
            //     'data' =>ProblemTypeResource::collection($problem_types),
            // ]);

        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
