<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;
use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use ApiTrait;

    public function fetch_post()
    {
        // try {
        $post = Post::whereUserId(null)->whereStatus(1)->first();
    
        if (!$post) {
            return $this->errorResponse('no posts found', 404);
        }
        $msg = "fetch_posts";
       return $data = new PostResource($post);
            // return $this->dataResponse($msg, $data, 200);
        // } catch (\Exception $ex) {
            // return $this->returnException($ex->getMessage(), 500);
        // }

    }
}
