<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ChatImageResource;
use App\Http\Resources\Api\ChatMessageResource;
use App\Http\Resources\Api\ChatResource;
use App\Models\Chat\BlockRequest;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatMessageMedia;
use App\Models\Chat\ChatUser;
use App\Models\Chat\UserImageRequest;
use App\Models\User\User;
use App\Models\User\UserBlock;
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
                'message' => 'sometimes',
                'image' => 'sometimes',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }


            $user = auth()->user();
            $receiver_id = $request->receiver_id;
            $receiver = User::where('id', $receiver_id)->first();
            if (!$receiver) {
                return $this->errorResponse('Receiver not exist', 404);
            }
            $message = $request->message;

            $userId = $user->id;
            $image = $user->images?->where('is_primary', 1)->first()->image_link ?? '';

            // $chat = Chat::whereHas('chat_users', function ($query) use ($user, $receiver) {
            //     $query->where('user_id', $user->id )->orWhere('user_id', $receiver->id);
            // })->first();
            $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $receiver->id])->first()?->chat;
            // Check if a chat already exists between the user and the receiver
            // $chat = Chat::where(function ($query) use ($user, $receiver) {
            //     $query->where('user_id', $user->id)
            //     ->where('receiver_id', $receiver->id);
            // })->orWhere(function ($query) use ($user, $receiver) {
            //     $query->where('user_id', $receiver->id)
            //     ->where('receiver_id', $user->id);
            // })->first();

            //         // if($user->is_verify == 1){
            if (!$chat) {
                // If no chat exists, create a new one
                $chat = Chat::create([
                    // "user_id" => $user->id,
                    // "receiver_id" => $receiver->id,
                    "name" => $receiver->name,
                ]);

                $chat->chat_users()->create([
                    "user_id" => $user->id,
                ]);

                $chat->chat_users()->create([
                    "user_id" => $receiver->id,
                ]);
            }

            $chatMessage = ChatMessage::create([
                "chat_id" => $chat->id,
                "user_id" => $user->id,
                "message" => $message,

            ]);


            if (($request->image)) {
                $document_data = upload_image($request->image, "chatMessage");
                $chatMessage->medias()->create([
                    'image' => $document_data,
                ]);

                // $user->update(['is_verify' => 1]);
            }
            $messages = ChatMessage::where('chat_id', $chat->id)
                ->orderBy('created_at', 'desc')
                ->pluck('id')
                ->toArray();

            $type = 4;
            // if (count($messages) == 1 && $receiver->user_device->device_token != null) {
            //     SendNotification::send($receiver->user_device->device_token, __('message.congratulations'), __('message.congrats_you_have_received_a_reply') , $type, $userId, url($image) ?? '');
            // }else{
            if ($receiver->user_device && $receiver->user_device->device_token != null) {
                SendNotification::send($receiver->user_device->device_token, __('messages.message'), $message, $type, $userId, url($image) ?? '');
            }
            // broadcast(new ChatMessageSent($message));



            // return $this->successResponse(__("message.sent successfully"), 200);
            return $this->dataResponse(__('message.sent successfully'), $chat, 200);

            // }else{
            //     $msg = __('message.your account is not verified');
            //     return $this->dataResponse($msg, 200);
            // }

        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }


    public function fetch_chats()
    {
        try {
            $user = auth()->user();
            // $sent_chats = Chat::where('user_id', $user->id)->where('receiver_id', '!=', $user->id);
            // $received_chats = Chat::where('receiver_id', $user->id)->where('user_id', '!=', $user->id);
            // $chats = $sent_chats->union($received_chats)->get();

            $chats = Chat::whereHas('chat_users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
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
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            // $chatId = $request->chat_id;
            $user = auth()->user();
            // if($user->is_verify == 1){

            $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;
            if (!$chat) {
                return $this->errorResponse(__('message.no chat found'), 200);
            }
            $messages = ChatMessage::where('chat_id', $chat->id)->orderBy('id', 'asc')->get();
            foreach ($messages as $message) {
                $message->update([
                    'is_read' => 1
                ]);
            }
            $msg = __('message.Messages');
            return $this->dataResponse($msg, ChatMessageResource::collection($messages), 200);
            // }else{
            //     $msg = __('message.your account is not verified');
            //     return $this->dataResponse($msg, 200);
            // }
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }


    public function delete_chat(Request $request)
    {
        try {
            $rules = [
                'chat_id' => 'required|exists:chats,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $chatId = $request->chat_id;
            $user = auth()->user();
            // if($user->is_verify == 1){

            $chat = ChatUser::where(['user_id' => $user->id, 'chat_id' => $chatId])->first();
            $chat->delete();
            $msg = __('message.delete_chat');
            return $this->successResponse($msg, 200);
            // }else{
            //     $msg = __('message.your account is not verified');
            //     return $this->dataResponse($msg, 200);
            // }
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function show_my_image(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $user = auth()->user();


            $chat_user = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first();
            $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;

            $my_chat_user = ChatUser::where('user_id', $user->id)->where('chat_id', $chat->id)->first();
            $chat_reciever = ChatUser::where('user_id', $request->user_id)->where('chat_id', $chat->id)->first();
            $my_chat_user->update([
                'image_status' => 1
            ]);
            $msg = __('message.show_my_image');
            return $this->dataResponse($msg,$my_chat_user->image_status ?? 0,200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function show_user_image(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $requester = auth()->user();
            $user = User::find($request->user_id);

            $chat = ChatUser::where(['user_id' => $requester->id, 'user_id' => $request->user_id])->first()?->chat;

            $data['requester_user_id'] = $requester->id;
            $data['user_id'] = $request->user_id;
            $data['chat_id'] = $chat->id;
            UserImageRequest::create($data);

            $title = __('message.request_for_image');
            $text = __('message.request_for_show_image');

            if (isset($user->devices) && $user->devices->count() > 0) {
                foreach ($user->devices as $user_device) {

                    SendNotification::send($user_device->device_token, $title, $text, 8, '',);
                }
            }
            $msg = __('message.success');
            return $this->successResponse($msg, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function accept_show_image(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $user = auth()->user();
            $image_request = UserImageRequest::where(['user_id' => $request->user_id, 'requester_user_id' => $user->id])->first();

            if (!$image_request) {
                $msg = __('message.request_not_found');
                return $this->errorResponse($msg, 400);
            }

            $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;

            $my_chat_user = ChatUser::where('user_id', $user->id)->where('chat_id', $chat->id)->first();
            $chat_reciever = ChatUser::where('user_id', $request->user_id)->where('chat_id', $chat->id)->first();

            $my_chat_user->update([
                'image_status' => 1
            ]);
            $msg = __('message.show_my_image');
            return $this->dataResponse($msg,$my_chat_user->image_status ?? 0,200);

        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }
    public function send_second_chance(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $requester = auth()->user();
            $user = User::find($request->user_id);
            $blocked_partner = UserBlock::where([['user_id', '=', $request->user_id], ['partner_id', '=', $requester->id]])->first();

            if (!$blocked_partner) {
                $msg = __('message.user_not_blocked');
                return $this->errorResponse($msg, 400);
            }
            $chat = ChatUser::where(['user_id' => $requester->id, 'user_id' => $request->user_id])->first()?->chat;

            $data['requester_user_id'] = $requester->id;
            $data['user_id'] = $request->user_id;
            $data['chat_id'] = $chat->id;
            BlockRequest::create($data);

            $title = __('message.request_for_unblock');
            $text = __('message.request_for_unblock');

            if (isset($user->devices) && $user->devices->count() > 0) {
                foreach ($user->devices as $user_device) {

                    SendNotification::send($user_device->device_token, $title, $text, 9, '',);
                }
            }
            $msg = __('message.send_second_chance');
            return $this->successResponse($msg, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function accept_second_chance(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $user = auth()->user();
            $block_request = BlockRequest::where(['user_id' => $request->user_id, 'requester_user_id' => $user->id])->first();

            if (!$block_request) {
                $msg = __('message.request_not_found');
                return $this->errorResponse($msg, 400);
            }

            $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;

            $my_chat_user = ChatUser::where('user_id', $user->id)->where('chat_id', $chat->id)->first();
            $chat_reciever = ChatUser::where('user_id', $request->user_id)->where('chat_id', $chat->id)->first();

            $my_chat_user->update([
                'block_status' => 1
            ]);
            $blocked_partner = UserBlock::where([['user_id', '=', $user->id], ['partner_id', '=', $request->user_id]])->first();
            $blocked_partner->delete();
            $msg = __('message.accept_second_chance');
            return $this->successResponse($msg, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }
}
