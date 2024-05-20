<?php

namespace App\Models\Chat;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $table = 'chat_messages';
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function medias(){
        return $this->hasMany(ChatMessageMedia::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
