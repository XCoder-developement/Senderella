<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CommentDataTable;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Models\Comment\Comment;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
    protected $view = 'admin_dashboard.comments.';
    protected $route = 'comments.';
    public function index(CommentDataTable $dataTable, $id)
    {
        $post = Post::whereId($id)->first();
        $dataTable->id = $id;
        return $dataTable->render($this->view . 'index', compact('id', 'post'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(Comment $comment)
    {
        //
    }


    public function edit(Comment $comment)
    {
        //
    }


    public function update(Request $request, Comment $comment)
    {
        //
    }


    public function destroy($id)
    {
        $post = Comment::whereId($id)->firstOrFail();
        $post->delete();
        return response()->json(['status' => true]);
    }
}
