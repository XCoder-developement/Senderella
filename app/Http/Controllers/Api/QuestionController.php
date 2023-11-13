<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\QuestionResource;
use App\Models\Question\Question;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiTrait;
    public function fetch_questions()
    {
        try {

            $questions = Question::get();
            //response

            $msg = "fetch_questions";

            return $this->dataResponse($msg,  QuestionResource::collection($questions), 200);
        } catch (\Exception $ex) {

            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
