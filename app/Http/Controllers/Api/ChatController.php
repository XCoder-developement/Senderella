<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Enums\NotificationTypeEnum;
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
use Illuminate\Support\Facades\DB;
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
        // try {
            $rules = [
                'receiver_id' => 'required|exists:users,id',
                'message' => 'sometimes|string|max:1000',
                'image' => 'sometimes|image|max:10240', // Optional validation for images
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }

            $user = auth()->user();
            $receiver = User::find($request->receiver_id);

            if (!$receiver) {
                return $this->errorResponse('Receiver not exist', 404);
            }

            $message = $request->message;
            $imageLink = $user->images?->where('is_primary', 1)->first()->image_link ?? '';

            $authChats = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $receiverChats = ChatUser::where('user_id', $request->receiver_id)->pluck('chat_id')->toArray();
            $chatId = array_intersect($authChats, $receiverChats);
            $chat = Chat::whereIn('id', $chatId)->first();

            if (!$chat) {
                $chat = Chat::create([
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

            if ($request->hasFile('image')) {
                $documentData = upload_image($request->image, "chatMessage");
                $chatMessage->medias()->create([
                    'image' => $documentData,
                ]);
            }

            $messageResource = new ChatMessageResource($chatMessage);
            $chatResource = new ChatResource($chat);
            $type = NotificationTypeEnum::CHAT->value;

            if ($receiver->user_device && $receiver->user_device->device_token) {
                SendNotification::send(
                    $receiver->user_device->device_token,
                    __('messages.message'),
                    $message,
                    $type,
                    $user->id,
                    url($imageLink) ?? '',
                    $messageResource,
                    $chatResource
                );
            }

            return $this->dataResponse(__('message.sent successfully'), $messageResource, 200);
        // } catch (\Exception $ex) {
        //     return $this->returnException($ex, 500);
        // }
    }



    public function fetch_chats()
    {
        try {
            $user = auth()->user();
            // $sent_chats = Chat::where('user_id', $user->id)->where('receiver_id', '!=', $user->id);
            // $received_chats = Chat::where('receiver_id', $user->id)->where('user_id', '!=', $user->id);
            // $chats = $sent_chats->union($received_chats)->get();
            $user_chats_ids = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $chats = Chat::whereIn('id', $user_chats_ids)->get();
            // dd( $user ,  $user_chats_ids ,$chats );
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
            $auth_chats = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = Chat::whereIn('id', $chat_id)->first();
            // dd($chat , $chat_id , $reciever_chats , $auth_chats , $user);
            if ($chat == null) {
                return $this->dataResponse(__('message.no chat found'), [], 200);
            }

            $messages = ChatMessage::where('chat_id', $chat->id)->orderBy('id', 'desc')->get();
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

            $chat = ChatUser::where('user_id', $user->id)->where(['chat_id', $chatId])->first();
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
            // $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;
            $auth_chats = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = ChatUser::whereIn('chat_id', $chat_id)->first();
            if (!$chat) {
                return $this->errorResponse(__('message.no chat found'), 200);
            }
            $my_chat_user = ChatUser::where('user_id', $user->id)->where('chat_id', $chat->id)->first();

            $chat_reciever = ChatUser::where('user_id', $request->user_id)->where('chat_id', $chat->id)->first();



            $my_chat_user->update([
                'image_status' => 1
            ]);
            $msg = __('message.show_my_image');
            return $this->dataResponse($msg, $my_chat_user->image_status ?? 0, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex, 500);
        }
    }

    public function show_user_image(Request $request)
    {
        // try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->getValidationErrors($validator);
            }
            $requester = auth()->user();
            $user = User::find($request->user_id);

            // $chat = ChatUser::where(['user_id' => $requester->id, 'user_id' => $request->user_id])->first()?->chat;
            $auth_chats = ChatUser::where('user_id', $requester->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = ChatUser::whereIn('chat_id', $chat_id)->first();
            $chat = Chat::find($chat->chat_id );
            // dd($chat , $auth_chats , $reciever_chats , $chat_id ,  $requester->id , $user  );
            if (!$chat) {
                return $this->successResponse(__('message.no chat found'), 200);
            }

            $chatExists = DB::table('chats')->where('id', $chat->chat_id)->exists();
            if (!$chatExists) {
                return $this->successResponse(__('message.no chat found'), 200);
            }
            $data['requester_user_id'] = $requester->id;
            $data['user_id'] = $request->user_id;
            $data['chat_id'] = $chat->id;
            // dd($data);
            UserImageRequest::create($data);

            $title = __('message.request_for_image');
            $text = __('message.request_for_show_image');
            $type = NotificationTypeEnum::SHOWUSERIMAGE->value;
            $image = $requester->images?->where('is_primary', 1)->first()->image_link ?? '';
            // dd($user->user_device , $user->user_device->device_token) ;
            if (isset($user->user_devices) && $user->user_devices->count() > 0) {
                foreach ($user->user_devices as $user_device) {

                    SendNotification::send(
                        $user_device->device_token,
                        $title,
                        $text,
                        $type,
                        $requester->id,
                        url($image),
                        '',
                        new ChatResource($chat),
                    );
                }
            }
            $msg = __('message.success');
            return $this->successResponse($msg, 200);
        // } catch (\Exception $ex) {
        //     return $this->returnException($ex, 500);
        // }
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
            $image_request = UserImageRequest::where('user_id' , $request->user_id)->where('requester_user_id' , $user->id)->first();

            if (!$image_request) {
                $msg = __('message.request_not_found');
                return $this->errorResponse($msg, 400);
            }

            // $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;
            $auth_chats = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = ChatUser::whereIn('chat_id', $chat_id)->first();


            $my_chat_user = ChatUser::where('user_id', $user->id)->where('chat_id', $chat->id)->first();
            $chat_reciever = ChatUser::where('user_id', $request->user_id)->where('chat_id', $chat->id)->first();

            $my_chat_user->update([
                'image_status' => 1
            ]);
            $msg = __('message.show_my_image');
            return $this->dataResponse($msg, new ChatResource($chat), 200);
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
            $imageLink = $requester->images?->where('is_primary', 1)->first()->image_link ?? '';

            $user = User::find($request->user_id);
            $blocked_partner = UserBlock::where('user_id', $request->user_id)->where('partner_id', $requester->id)->first();

            if (!$blocked_partner) {
                $msg = __('message.user_not_blocked');
                return $this->errorResponse($msg, 400);
            }
            // $chat = ChatUser::where(['user_id' => $requester->id, 'user_id' => $request->user_id])->first()?->chat;
            $auth_chats = ChatUser::where('user_id', $requester->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = ChatUser::whereIn('id', $chat_id)->first();
            $chat = Chat::find($chat->chat_id );

            $data['requester_user_id'] = $requester->id;
            $data['user_id'] = $request->user_id;
            $data['chat_id'] = $chat->id;
            BlockRequest::create($data);

            $title = __('message.request_for_unblock');
            $text = __('message.request_for_unblock');
            $type = NotificationTypeEnum::SECONDCHANCE->value;

            if (isset($user->user_devices) && $user->user_devices->count() > 0) {

                foreach ($user->user_devices as $user_device) {

                    SendNotification::send(
                        $user_device->device_token,
                        $title,
                        $text,
                        $type,
                        $requester->id,
                        url($imageLink),
                        '',
                        new ChatResource($chat)
                    );
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
            $block_request = BlockRequest::where('user_id' , $request->user_id)->where('requester_user_id' , $user->id)->first();

            if (!$block_request) {
                $msg = __('message.request_not_found');
                return $this->errorResponse($msg, 400);
            }

            // $chat = ChatUser::where(['user_id' => $user->id, 'user_id' => $request->user_id])->first()?->chat;
            $auth_chats = ChatUser::where('user_id', $user->id)->pluck('chat_id')->toArray();
            $reciever_chats = ChatUser::where('user_id', $request->user_id)->pluck('chat_id')->toArray();
            $chat_id = array_intersect($auth_chats, $reciever_chats);
            $chat = ChatUser::whereIn('chat_id', $chat_id)->first();

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
