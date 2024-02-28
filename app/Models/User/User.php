<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
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
use App\Models\UserSearch\UserSearch;
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

    protected $appends = ['date_format', 'user_age'];

    public function getDateFormatAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d g:i A');
    }
    public function getUserAgeAttribute()
    {
        return Carbon::parse($this->birthday_date)->age;
    }

    public function getLastActiveDateAttribute(){
        return $this->last_shows !== null && $this->last_shows->first() ? $this->last_shows?->first()?->end_date : Carbon::now()->format('Y-m-d H:i:s');
    }
    public function scopeAgeRange($query, $ageFrom, $ageTo)
    {
        //Calculate request age by subtracting[2023-25=1998] it from the current year
        //birtday_date = 1998-02-01
        //age_from = 20
        //age_to = 25
        $dateFrom = now()->subYears($ageTo)->addDay();
        //calc 2023 - 20 = 2003
        //calc 2023 - 25 = 2008
        //not equal!! if birthday_date = 2003-02-01
        $dateTo = now()->subYears($ageFrom);
        //return birtd_date between 2003-02-01 and 2008-02-01
        return $query->orwhereBetween('birthday_date', [$dateFrom, $dateTo]);
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


    public function getIsFollowAttribute()
    {
        if ($this->is_follow == 1) {
            return __('messages.is followed');
        } elseif ($this->is_follow == 0) {
            return __('messages.not followed');
        }
    }

    public function is_follow($user_id){
        $favorite_product = UserLike::whereUserId($user_id)->wherePartnerId($this->id)->first();
        if($favorite_product){
            return 1;
        }elseif(!$favorite_product){
            return 0;
        }
    }


    //liked and following partners
    public function following(): HasMany
    {
        return $this->hasMany(UserLike::class, 'user_id');
    }

    //liked by and follower partners
    public function followers(): HasMany
    {
        return $this->hasMany(UserLike::class, 'partner_id');
    }


    //blocked partner who user blocks
    public function blocked(): HasMany
    {
        return $this->hasMany(UserBlock::class, 'user_id');
    }


    //blocker who blocks the user
    public function blocker(): HasMany
    {
        return $this->hasMany(UserBlock::class, 'partner_id');
    }

    //who the user watches
    public function Watched(): HasMany
    {
        return $this->hasMany(UserWatch::class, 'user_id');
    }


    //blocker who blocks the user
    public function watcher(): HasMany
    {
        return $this->hasMany(UserWatch::class, 'partner_id');
    }

    public function is_favorite($user_id){
        $favorite_product = UserBookmark::whereUserId($user_id)->wherePartnerId($this->id)->first();
        if($favorite_product){
            return 1;
        }elseif(!$favorite_product){
            return 0;
        }
    }
    //who the user favorites
    public function favorited(): HasMany
    {
        return $this->hasMany(UserBookmark::class, 'user_id');
    }


    //who favorites the user
    public function favorited_by(): HasMany
    {
        return $this->hasMany(UserBookmark::class, 'partner_id');
    }

    public function chat(): HasMany{
        // this relationship belongs to chat and ordered by which creating recently
        return $this->hasMany(Chat::class)->orderBy('created_at', 'asc');
    }

    public function chat_message(): HasMany{
        // this relationship belongs to chatmessage and ordered by which creating recently

        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function last_shows(): HasMany{
        return $this->hasMany(UserLastShow::class);
    }

    public function search(): HasMany{
        return $this->hasMany(UserSearch::class);
    }
    //like me and followers partners
    // public function followers(): HasMany
    // {
    //     return $this->hasMany(UserLike::class ,'partner_id');
    // }

    // public function block_partners(): HasMany
    // {
    //     return $this->likes->whereIn('partner_id',auth()->id());
    // }
}
