<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Color\Color;
use App\Models\Gift\Gift;
use App\Models\Post\Post;
use App\Models\Order\Order;
use App\Models\Comment\Comment;
use App\Models\EducationType\EducationType;
use App\Models\Product\Product;
use App\Models\Setting\Setting;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Location\State\State;
use PhpParser\Node\Expr\Cast\Double;
use App\Models\UserQuestion\UserAnswer;
use App\Models\Location\Country\Country;
use App\Models\MaritalStatus\MaritalStatus;
use App\Models\MarriageReadiness\MarriageReadiness;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification\Notification;
use App\Models\UserQuestion\UserQuestion;
use Carbon\Carbon;
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

    protected $appends = ['date_format'];

    public function getDateFormatAttribute(){
        return Carbon::parse($this->created_at)->format('Y-m-d g:i A') ;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function marital_status(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function education_type(): BelongsTo
    {
        return $this->belongsTo(EducationType::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function marriage_readiness(): BelongsTo
    {
        return $this->belongsTo(MarriageReadiness::class);
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
    public function likes(): HasMany
    {
        return $this->hasMany(UserLike::class);
    }
    public function blocks(): HasMany
    {
        return $this->hasMany(UserLike::class);
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

    // public function followers(): HasMany
    // {
    //     return $this->likes->whereIn('user_id',auth()->id());
    // }

    // public function following(): HasMany
    // {
    //     return $this->likes->whereIn('partner_id',auth()->id());
    // }

    // public function block_partners(): HasMany
    // {
    //     return $this->likes->whereIn('partner_id',auth()->id());
    // }
}
