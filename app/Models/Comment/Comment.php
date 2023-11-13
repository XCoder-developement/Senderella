<?php

namespace App\Models\Comment;

use App\Models\Like\Like;
use App\Models\Post\Post;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function postsCount()
    {
        return $this->hasMany(Post::class)->withCount('posts');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
