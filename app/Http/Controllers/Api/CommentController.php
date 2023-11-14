<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Models\Comment\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Comment\LikeCommentRequest;

class CommentController extends Controller
{
    use ApiTrait;
    public function store(CommentRequest $request)
    {
        try {
            $user = auth()->user();
            $post = Post::find($request->post_id);
            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }
            $comment = $post->comments()->create([
                'user_id' => $user->id,
                'comment' => $request->comment
            ]);
            $msg = 'commented  successfully';
            $data = new CommentResource($comment);
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
    public function likeComment(LikeCommentRequest $request)
    {

        try {
            $user = auth()->user();
            $comment = Comment::find($request->comment_id);
            if (!$comment) {
                return response()->json(['error' => 'Post not found'], 404);
            }
            $existingLike = $comment->likes()
                ->where('user_id', $user->id)
                ->where('likeable_id', $request->comment_id)
                ->where('likeable_type', 'App\Models\Comment\Comment')
                ->first();
            if ($existingLike) {
                $existingLike->delete();
                return response()->json(['message' => 'Comment like removed successfully'], 200);
            } else {
                $comment->likes()->create([
                    'user_id' => $user->id,
                ]);
                $msg = 'Comment liked successfully';
                $data = new CommentResource($comment);
                return $this->dataResponse($msg, $data, 200);
            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
