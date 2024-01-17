<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ChatResource;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    //
    use ApiTrait;
    public function create_chat(Request $request){
        try{
            $user=auth()->user();
            $reciver_id=$request->reciver_id;
            $reciver = User::find($reciver_id)->get();
            Chat::create([
                "name" => $reciver->name,
                "user_id" => $user->id,
                "reciver_id" => $reciver_id,
            ]);

            return $this->successResponse("Chat created successfully", 200);
        }
        catch(\Exception $ex){
            return $this->returnException($ex, 500);
        }
    }

    public function send_message(Request $request){
        try{
            $rules = [
                // "chat_id" => "required",
                'receiver_id' => 'required',
                'message' => 'required',
                ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {

                return $this->getvalidationErrors($validator);

            }

            $user=auth()->user();
            $reciver_id=$request->receiver_id;
            $reciver = User::find($reciver_id)->first();
            $message=$request->message;
//             $chat=Chat::find($request->chat_id)->get();
// dd($chat);
            $chatMessage = ChatMessage::create([
                "user_id" => $user->id,
                "receiver_id" => $reciver->id,
                "message" => $message,
            ]);

            broadcast(new ChatMessageSent('chat', $chatMessage)); // here I brodcast the message
            return $this->successResponse("message.Message sent successfully", 200);
    }catch(\Exception $ex){
        return $this->returnException($ex, 500);
    }
}

    public function fetch_chats(){
        try{
            $user=auth()->user();
            $chats =ChatMessage::where('user_id', $user->id)->get();
            // dd($chats);
            $data = ChatResource::collection($chats);
            $msg = __('message.Your chats');
            return $this->dataResponse($msg , $data, 200);
        }
        catch(\Exception $ex){
            return $this->returnException($ex, 500);
        }
    }
}
