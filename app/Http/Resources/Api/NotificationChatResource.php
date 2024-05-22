<?php

namespace App\Http\Resources\Api;

use App\Models\Chat\ChatMessage;
use App\Models\User\User;
use App\Models\User\UserBlock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chat_parts = $this->chat_users->pluck('user_id')->toArray();
        $user = User::whereIn('id', $chat_parts)->whereNot('id', auth()->id())->first();
        // $user = auth()->user();
        $auth = User::find(auth()->id() );
        $message = ChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->value('message');
        $last_message = ChatMessage::where('chat_id', $this->id)->orderBy('created_at', 'desc')->first();
        if($user){
            $is_blocked = UserBlock::where('user_id', $user->id)->where('partner_id', auth()->id())->first();
            }else{
                $is_blocked = null;
            }
        return [
            'chat_id' => $this->id,
            'name'   => $this->name ?? '',
            'message'   => $message ?? '',
            'date' => $last_message?->created_at->format('Y-m-d') ?? '',
            'time' => $last_message?->created_at->format('g:i A') ?? '',

            'sender_id' => $last_message?->user_id ?? '',
            'new_message_count' => $this->messages()->where('is_read', 0)->whereNot('user_id', auth()->id())->count(),
            'partner' => new PartnerResource($auth),
            'show_my_image' => $this->chat_users->where('user_id',auth()->id())->first()?->image_status ?? 0,
            'show_user_image' => $this->chat_users->where('user_id','!=',auth()->id())->first()?->image_status ?? 0,
            'is_blocked' => $is_blocked? 1 : 0,

            // $message = $user->is_verify == 1 ? $message : substr($message, 0, 5) . encrypt(substr($message, 5)),
        ];
    }
}
