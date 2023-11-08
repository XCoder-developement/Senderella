<?php

namespace App\Models\MovingPlace;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovingPlace extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'moving_place_id';
    protected $table = 'moving_places';
    protected $guarded = [];

}
