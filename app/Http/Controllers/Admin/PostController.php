<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ApiTrait;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Models\Post\PostImage;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\DataTables\Admin\PostDataTable;
use App\Http\Enums\NotificationTypeEnum;
use App\Http\Requests\Admin\Post\PostRequest;
use App\Models\User\User;
use App\Models\User\UserNotification;
use App\Services\SendNotification;
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
        $users = User::all();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['post' => $request['post-' . $localeCode]];
        }
        $data['admin_id'] = auth()->user()->id ?? null;
        $post = Post::create($data);
        $type = NotificationTypeEnum::NEWPOST->value; ;
        foreach($users as $user){
            $user->update(['is_post_shown' => $user->is_post_shown + 1]);
            $user->update(['is_notification_shown' => $user->is_notification_shown + 1]);
            if(optional($user->user_device)->device_token){
            SendNotification::send($user->user_device->device_token,__('messages.new_post'),__('messages.new_post') , $type ,'' , '');
            UserNotification::create([
                'user_id' => $user->id,
                'title' => __('messages.new_post'),
            ]);
        }
        }

        if ($request->has('images') && count($request->images) > 0) {
            foreach ($request->images as $image) {
                $image_data = upload_image($image, "posts");
                PostImage::create([
                    'image' => $image_data,
                    'post_id' => $post->id,
                ]);
            }
        }
        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }


    public function show(Post $post)
    {

    }


    public function edit($id)
    {
        $post = Post::whereId($id)->first();

        return view($this->view . 'edit', compact('post'));
    }


    public function update(Request $request, $id)
    {
        $post = Post::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['post' => $request['post-' . $localeCode],];
        }
        $post->update($data);
        if ($request->has('images') && count($request->images) > 0) {
            foreach ($request->images as $image) {
                $image_data = upload_image($image, "posts");
                PostImage::create([
                    'image' => $image_data,
                    'post_id' => $post->id,
                ]);
            }
        }
        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $post = Post::whereId($id)->firstOrFail();
        $post->delete();
        return response()->json(['status' => true]);
    }

    public function active_post(Request $request)
    {
        $post = Post::whereId($request->post_id)->first();
        $data['status'] = $post->status ? 0 : 1;
        $post->update($data);
        return response()->json([
            'status' => true
        ]);
    }
}
