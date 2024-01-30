<?php

namespace App\Services;

use App\Services\Constants;

class SendNotification
{

    private static $URL = "https://fcm.googleapis.com/fcm/send";

    public static function send($token, $title,$text , $image = null)
    {
        $data = [
            "to" =>$token,
            "data" =>[
                    "title" => $title,
                    'body' => $text,
                    'image' => $image,
                    // "type" => $type,
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK"
                ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . Constants::NOTIFICATION_KEY,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $result=curl_exec($ch);
        return true;
    }


    //Karim notification function

    // public static function send($token,$title,$text)
    // {

    //     define( 'API_ACCESS_KEY', 'AAAAyyhCrck:APA91bGVL8jV60PLexarNcGt7eshE1WjJWP6U3T3g7zYriccXZwHlbAhnO_Ip-eyh-5ZDMy9444HQUj0zNKdSrJjWhwIntxi0Ynac8I590UE_4wwSbpryMPf1UtgDkx96YZz0b4CECAi' );
    //     $registrationIds = array( $_GET['id'] );
    //     $msg= [
    //         'body' => $text,
    //         'title' => $title,
    //         'vibrate' => 1,
    //         'sound' =>1,

    //     ];

    //     $fields = [
    //         'registration_ids'=> $registrationIds,
    //         'notification'=>$msg,
    //     ];


    //     $headers = [
    //         'Authorization: key=' . Constants::NOTIFICATION_KEY,
    //         'Content-Type: application/json'
    //     ];
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    //     $result=curl_exec($ch);
    //     curl_close($ch);

    //     echo $result;
    // }
}
