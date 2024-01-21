<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function fetch_notifications(){

        $user = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->get();
        // dd($notifications);
        $user->update(['is_notification_shown' => 0]);
        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);

    }
}
