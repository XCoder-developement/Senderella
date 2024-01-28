<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ChatResource;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User\User;
use App\Services\SendNotification;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    //
    use ApiTrait;
    public function create_chat(Request $request)
    {
        try {
            $user = auth()->user();
            $reciver_id = $request->reciver_id;
            $reciver = User::find($reciver_id)->first();
            Chat::create([
                "name" => $reciver->name,
                "user_id" => $user->id,
                "reciver_id" => $reciver_id,
            ]);

            return $this->successResponse("Chat created successfully", 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function send_message(Request $request)
    {
        try {
            $rules = [
                'receiver_id' => 'required',
                'message' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }

            $user = auth()->user();
            $receiver_id = $request->receiver_id;
            $receiver = User::where('id' , $receiver_id)->first();
            $message = $request->message;

            $chat = Chat::updateOrCreate([
                "user_id" => $user->id,
                "receiver_id" => $receiver->id,
                "name" => $receiver->name,
            ]);

            $chatMessage = ChatMessage::create([
                "chat_id" => $chat->id,
                "user_id" => $user->id,
                "receiver_id" => $receiver->id,
                "message" => $message,
            ]);

            $messages = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('created_at', 'desc')
            ->pluck('id')
            ->toArray();
            if(count($messages) == 1){
                SendNotification::send($receiver->user_device->device_token , __('message.congratulations'), __('message.congrats you have recieved a reply'));
            }
            // Broadcasting to a private channel based on receiver_id
            // broadcast(new ChatMessageSent($chatMessage))->toOthers();

            return $this->successResponse("Message sent successfully", 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }


    public function fetch_chats()
    {
        try {
            $user = auth()->user();
            $sent_chats = Chat::where('user_id', $user->id)->get();
            $recived_chats = Chat::where('receiver_id', $user->id)->get();
            $chats = $sent_chats->merge($recived_chats);
            // dd($recived_chats);
            // dd($chats);
            $data = ChatResource::collection($chats);
            $msg = __('message.Your chats');
            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function fetch_messages(Request $request)
    {
        try {
            $rules = [
                'chat_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $chatId = $request->chat_id;
            $user = auth()->user();
            $messages = ChatMessage::where('chat_id', $chatId)->where('receiver_id' , $user->id)->orderBy('created_at', 'desc')->get();
            $msg = __('message.Messages');
            return $this->dataResponse($msg, $messages, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }
}
