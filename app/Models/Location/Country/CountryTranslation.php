<?php

namespace App\Models\Location\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    use HasFactory;
    protected $table = 'country_translations';
    protected $fillable =  ['title'];
}
