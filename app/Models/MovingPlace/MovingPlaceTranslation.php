<?php

namespace App\Models\MovingPlace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovingPlaceTranslation extends Model
{
    use HasFactory;
    protected $table = 'moving_place_translations';
    protected $fillable =  ['title'];
}
