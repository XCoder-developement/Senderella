<?php

namespace App\Models\Chat;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockRequest extends Model
{
    use HasFactory;
    protected $table = 'block_requests';
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function chat(){
        return $this->belongsTo(Chat::class);
    }
}
