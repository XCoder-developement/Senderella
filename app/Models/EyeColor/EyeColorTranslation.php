<?php

namespace App\Models\EyeColor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeColorTranslation extends Model
{
    use HasFactory;
    protected $table = 'eye_color_translations';
    protected $fillable = ['title'];
}
