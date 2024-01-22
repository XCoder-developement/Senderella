<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\LikePostResource;
use App\Http\Requests\Post\LikePostRequest;

class PostController extends Controller
{
    use ApiTrait;

    public function fetch_post()
    {
        $user = auth()->user();
        try {
            $post = Post::where('status', 1)
                // ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->with('user')
                // ->whereHas('user', function ($query) {
                //     $query->where('is_post_shown', '!=', 0);
                // })
                ->first();
            // dd($user->is_post_shown);
            if (!$post) {
                return $this->dataResponse('no posts found', [] , 200);
            }
            $user->update(['is_post_shown' => 0]);

            $msg = "fetch_posts";
            $data = new PostResource($post);;
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function likePost(LikePostRequest $request)
    {
        try {
            $user = auth()->user();
            $post = Post::find($request->post_id);

            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            $existingLike = $post->likes()
                ->where('user_id', $user->id)
                ->where('likeable_id', $request->post_id)
                ->where('likeable_type', 'App\Models\Post\Post')
                ->first();
            if ($existingLike) {
                $existingLike->delete();
                $msg = 'The like has been removed';
                $data = new LikePostResource($post);
                return $this->dataResponse($msg, $data, 200);
            } else {
                $post->likes()->create([
                    'user_id' => $user->id,
                ]);
                $msg = 'message.Post liked successfully';
                $data = new LikePostResource($post);
                return $this->dataResponse($msg, $data, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
