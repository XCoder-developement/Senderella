<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\RequirmentResource;
use App\Models\Requirment\Requirment;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class RequirmentController extends Controller
{
    //
    use ApiTrait;
    public function fetch_requirments(){
        $requirements = Requirment::where('answer_type',1)->get();
        $data = RequirmentResource::collection($requirements);
        $msg='fetch_rquirments';
        return $this->dataResponse($msg, $data ,200);
    }

    public function fetch_user_questions(){
        $user_questions = Requirment::where('answer_type',2)->get();
        $data = RequirmentResource::collection($user_questions);
        $msg='fetch_user_questions';
        return $this->dataResponse($msg, $data ,200);
    }


}
