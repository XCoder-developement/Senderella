<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\NotificationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\StoreRequest;
use App\Http\Requests\Admin\Notification\UpdateRequest;
use App\Models\Notification\Notification;
use App\Models\User\User;
use App\Services\SendNotification;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NotificationController extends Controller
{
    //
    protected $view = 'admin_dashboard.notifications.';
    protected $route = 'notifications.';



    public function index()
    {
        return view($this->view . 'index');
    }


    public function send(StoreRequest $request)
    {

        $title = $request->title;
        $text = $request->body;


        $notify_data["title"] = $title;
        $notify_data["body"] = $text;

        $notification  = Notification::create($notify_data);
        $users = User::whereHas('user_device' ,function($q){
            $q->whereNotNull('device_token');
        })->get();
        // foreach($users as $user){
        //     // $user->notifications()->attach($notification);
        //     foreach($user->user_devices as $user_device){

                SendNotification::send('dXWS6yLdT_aZTcxE5EWLoN:APA91bHfTdjBmPHikfzo2e0_LPkSp1vdfN46CQDZBrK5IlUmGqoCifwJyojkg5NXLXBl3wO1_BZEOcL9YeCxh3M9kCrlTA7wOUFtlhrCWhg_ASc-O3BkF2XVAal_TG5i-sQ8xPfymKxi' ?? "",$title,$text);
            // }
            // }


        return redirect()->back()->with(['success'=> __("messages.send_notification")]);
    }

}
