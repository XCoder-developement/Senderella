<?php

namespace App\Models\Comment;

use Carbon\Carbon;
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
    protected $appends = ['date_format'];

    public function getDateFormatAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d g:i A');
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
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
        return $this->morphMany(Like::class, 'likeable');
    }
    public function likesCount()
    {
        return $this->hasMany(Like::class)->withCount('likes');
    }
}
