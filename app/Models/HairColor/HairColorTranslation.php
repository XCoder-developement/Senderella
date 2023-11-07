<?php

namespace App\Models\HairColor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairColorTranslation extends Model
{
    use HasFactory;
    protected $table = 'hair_color_translations';
    protected $fillable =  ['title'];
}
