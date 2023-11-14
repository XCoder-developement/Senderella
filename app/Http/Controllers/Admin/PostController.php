<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    use ApiTrait;

    public function index()
    {
        try {
            $posts = Post::orderBy("created_at", "desc")->paginate(10);
            $msg = "fetch_posts_descending";
            $data = PostResource::collection($posts);
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Post $post)
    {
        //
    }


    public function edit(Post $post)
    {
        //
    }


    public function update(Request $request, Post $post)
    {
        //
    }


    public function destroy(Post $post)
    {
        //
    }

}
