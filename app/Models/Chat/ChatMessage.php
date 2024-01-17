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
        // this relationship belongs to user
        return $this->belongsTo(User::class);
    }
}
