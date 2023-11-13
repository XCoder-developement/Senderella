<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Gift\Gift;
use App\Models\Post\Post;
use App\Models\Order\Order;
use App\Models\Comment\Comment;
use App\Models\Product\Product;
use App\Models\Setting\Setting;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Location\State\State;
use PhpParser\Node\Expr\Cast\Double;
use App\Models\UserQuestion\UserAnswer;
use App\Models\Location\Country\Country;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification\Notification;
use App\Models\UserQuestion\UserQuestion;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    protected $with = ["country", "state"];


    public function user_devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }
    public function informations(): HasMany
    {
        return $this->hasMany(UserInformation::class);
    }
    public function user_device(): HasOne
    {
        return $this->hasOne(UserDevice::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(UserImage::class);
    }

    // public function notifications():BelongsToMany {
    //     return $this->belongsToMany(Notification::class,"user_notifications","user_id","notification_id");
    // }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    
    public function getIsMarriedBeforeFormatAttribute()
    {
        if ($this->is_married_before == 2) {
            return __('messages.no');
        } elseif ($this->is_married_before == 1) {
            return __('messages.yes');
        }
    }

    public function getGenderFormatAttribute()
    {
        if ($this->gender == 2) {
            return __('messages.female');
        } elseif ($this->gender == 1) {
            return __('messages.male');
        }
    }
}
