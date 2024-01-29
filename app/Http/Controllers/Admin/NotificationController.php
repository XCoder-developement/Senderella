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

                SendNotification::send('eFv4EhijTnmQukqkZjJckf:APA91bHCaPPjskbQ45lBcI4Yf3pSv8ivRsv-REpB0qHEJN43CPg1aK282mKIWCPjuFI21DgMVhpz2iUXW3fhUEN4o9LT9CCg0Nh_TqUSX7owe42Zr0wdzonac47O33hzUtge5RKgb8Ix' ?? "",$title,$text);
            // }
            // }


        return redirect()->back()->with(['success'=> __("messages.send_notification")]);
    }

}
