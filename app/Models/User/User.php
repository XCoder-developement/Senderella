<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\City\City;
use App\Models\Gift\Gift;
use App\Models\Notification\Notification;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Setting\Setting;
use App\Models\State\State;
use App\Models\Zone\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Expr\Cast\Double;

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

    protected $appends = ["image_link"];

    public function getImageLinkAttribute()
    {
        return $this->image ? asset($this->image) : '';
    }

    public function state():BelongsTo {
        return $this->belongsTo(State::class);
    }
    public function city():BelongsTo {
        return $this->belongsTo(City::class);
    }
    public function zone():BelongsTo {
        return $this->belongsTo(Zone::class);
    }
    protected $with = ["state","city","zone"];

    public function user_devices():HasMany {
        return $this->hasMany(UserDevice::class);
    }
    public function user_device():HasOne {
        return $this->hasOne(UserDevice::class);
    }
    public function notifications():BelongsToMany {
        return $this->belongsToMany(Notification::class,"user_notifications","user_id","notification_id");
    }
    public function favorite_products():BelongsToMany {
        return $this->belongsToMany(Product::class,"user_favorite_products","user_id","product_id");
    }

    public function orders():HasMany {
        return $this->hasMany(Order::class);
    }

    public function points():HasMany {
        return $this->hasMany(UserPoint::class);
    }

    public function convert_money_to_points($money):float{
        $setting = Setting::first();

        $points = 0;

        if($setting && $setting->points && $setting->money){

        $points = ($money * ($setting->points)) / $setting->money;
        }
        return $points;
    }

    public function gifts():BelongsToMany {
        return $this->belongsToMany(Gift::class,"user_gifts","user_id","gift_id");
    }
}
