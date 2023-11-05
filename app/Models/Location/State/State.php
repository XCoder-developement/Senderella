<?php

namespace App\Models\Location\State;

use App\Models\Joiner\Joiner;
use App\Models\Location\Country\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class State extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'state_id';
    protected $table = 'states';
    protected $guarded = [];

    protected $with = ["country"];

    public function country(){
        return $this->belongsTo(Country::class);
    }


}

