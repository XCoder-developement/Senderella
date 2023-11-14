<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PostDataTable;
use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\Admin\Post\PostRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PostController extends Controller
{
    protected $view = 'admin_dashboard.posts.';
    protected $route = 'posts.';
    public function index(PostDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }


    public function create()
    {
        return view($this->view . 'create');

    }


    public function store(PostRequest $request)
    {
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = $request['post-' . $localeCode];
        }
        $data['admin_id'] = auth()->user()->id ?? null;
        // dd($data);
        Post::create($data);
        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }


    public function show(Post $post)
    {
        //
    }


    public function edit(Post $post)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $post = Post::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['post', $request['post-' . $localeCode],];
        }
        // $data['user_id'] = auth()->user()->id ?? null;
        $post->update($data);
        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $post = Post::whereId($id)->firstOrFail();
        $post->delete();
        return response()->json(['status' => true]);
    }

}
