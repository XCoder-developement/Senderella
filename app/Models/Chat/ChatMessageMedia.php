<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageMedia extends Model
{
    use HasFactory;
    protected $table = 'chat_message_medias';
    protected $guarded = [];

    protected $appends = ["image_link"];

    public function getImageLinkAttribute()
    {
        return  $this->image ? asset($this->image) : '';
    }

    public function message(){
        return $this->belongsTo(ChatMessage::class);
    }
}
