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

    //     $notification  = Notification::create($notify_data);
    //     $users = User::whereHas('user_device' ,function($q){
    //         $q->whereNotNull('device_token');
    //     })->get();

    //     foreach($users as $user){
    //         // $user->notifications()->attach($notification);
    //         foreach($user->user_devices as $user_device){

    //         SendNotification::send($user_device->device_token ?? "",$title,$text);

    //         }
    //         }


    //     return redirect()->back()->with(['success'=> __("messages.send_notification")]);
    // }
    $url = 'https://fcm.googleapis.com/fcm/send';

    $FcmToken = 'eFv4EhijTnmQukqkZjJckf:APA91bHCaPPjskbQ45lBcI4Yf3pSv8ivRsv-REpB0qHEJN43CPg1aK282mKIWCPjuFI21DgMVhpz2iUXW3fhUEN4o9LT9CCg0Nh_TqUSX7owe42Zr0wdzonac47O33hzUtge5RKgb8Ix';

    $serverKey = 'AAAAC0sn-aE:APA91bGu_WwGma-HWgCYWeWKTOoQ9VXxpvTzp3ZIR_Fk-BAQ6Qeo_fCMvtT89T_f-1YPgt4OcDfK-gpj1yOHQYpMIdkfluo-GWt2HxnynOJmkvT2_jEvVsL2V_w5JekDT1Jlp7pDaYTo'; // ADD SERVER KEY HERE PROVIDED BY FCM

    $data = [
        "registration_ids" => $FcmToken,
        "notification" => [
            "title" => $request->title,
            "body" => $request->body,
        ]
    ];
    $encodedData = json_encode($data);

    $headers = [
        'Authorization:key=' . $serverKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
    // FCM response
    }

}
