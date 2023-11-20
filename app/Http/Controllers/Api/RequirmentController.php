<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\RequirmentResource;
use App\Http\Resources\Api\UserQuestionResource;
use App\Models\Requirment\Requirment;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class RequirmentController extends Controller
{
    //
    use ApiTrait;
    public function fetch_requirments()
    {
        try {

            $requirments = Requirment::where('answer_type', 1)->get();

            $data = RequirmentResource::collection($requirments);

            $msg = 'fetch_rquirments';

            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {

            return $this->returnException($ex->getMessage(), 500);
        }
    }


    public function fetch_user_questions()
    {
        try {
            $user = auth()->user();

            $user_questions = Requirment::where('answer_type', 2)->get();

           

            $data = UserQuestionResource::collection($user_questions);

            // if($user_answers && $user_answers->pluck('answer')->toArray() != null){

            // }

            $msg = 'fetch_user_questions';

            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {

            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
