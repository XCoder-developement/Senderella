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
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
