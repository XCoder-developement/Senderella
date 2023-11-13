<?php

namespace App\Models\Like;

use App\Models\Post\Post;
use App\Models\Comment\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $tabe = 'likes';

    public function likeable()
    {
        return $this->morphTo();
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'likeable');
    }
    public function comments()
    {
        return $this->belongsTo(Comment::class, 'likeable');
    }
}
