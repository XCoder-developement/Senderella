<?php

namespace App\Http\Middleware;

use App\Models\Teacher\Teacher;
use Closure;
use Illuminate\Http\Request;
use  App\Traits\ApiTrait;

class ChackApiKey
{
    use ApiTrait;

    public function handle(Request $request, Closure $next)
    {
        $api_key = $request->header("api_key");
        //chek if api key exists
        if ($api_key == null) {
            return $this->errorResponse("api key is empty add it ", 400);
        }
        $teacher = Teacher::whereApiKey($api_key)->first();

        //chek if teacher exists

        if ($teacher) {
            return $next($request);
        }


        return $this->errorResponse("api key is invaild", 400);
    }
}
