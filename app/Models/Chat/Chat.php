<?php

namespace App\Models\Chat;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $guarded = [];
    public function chat_users()
    {
        // this relationship belongs to user
        return $this->hasMany(ChatUser::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function messages_count($partner_id){
        $count = ChatMessage::where('user_id' , $partner_id)->where('is_read' , 0)->get();
        return $count->count();
    }
}
