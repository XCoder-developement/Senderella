<?php

namespace App\Models\Post;

use App\Models\Like\Like;
use App\Models\User\User;
use App\Models\Comment\Comment;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['post'];
    public $translationForeignKey = 'post_id';
    public $table = 'posts';
    protected $guarded = [];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function likesCount()
    {
        return $this->hasMany(Like::class)->withCount('likes');
    }
    public function comments()
    {
        return $this->HasMany(Comment::class);
    }
    public function commentsCount()
    {
        return $this->hasMany(Comment::class)->withCount('comments');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


