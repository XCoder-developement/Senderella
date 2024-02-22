<?php

namespace App\Http\Services;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class WhatsappService
{
    use ApiTrait;
    protected $apiService;

    public function screenshot_session()
    {
        try {
            $apiUrl = 'http://crazyidea.online:****/api/screenshot?session=default';

            $headers = [
                'Accept' => 'image/png',
            ];

            $response = Http::withHeaders($headers)->get($apiUrl);

            return $response->body();
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }

    public function send_otp_by_method($code ,$method_id ,$user ,$verification_code )
    {
        try {
            if($method_id == 2){

            $apiUrl = 'http://crazyidea.online:3009/api/sendText?phone='. $code . $user->phone . '&text=' . $verification_code . '&session=default';
            Http::get($apiUrl);

            }
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
