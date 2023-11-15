<?php

namespace App\Models\Post;

use Carbon\Carbon;
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

    protected $appends = ['date_format'];

    public function getDateFormatAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d g:i A');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
