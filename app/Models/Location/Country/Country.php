<?php

namespace App\Models\Location\Country;

use App\Models\Joiner\Joiner;
use App\Models\Location\City\City;
use App\Models\Location\State\State;
use App\Models\Package\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Country extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'country_id';
    protected $table = 'countries';
    protected $guarded = [];

    public function states(){
        return $this->hasMany(State::class);
    }

    protected $appends = ["image_link"];

    public function getImageLinkAttribute()
    {
        return $this->image ? asset($this->image) : '';
    }

}
