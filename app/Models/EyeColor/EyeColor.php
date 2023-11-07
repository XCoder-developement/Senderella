<?php

namespace App\Models\EyeColor;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeColor extends Model
{
    use HasFactory ,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'eye_color_id';
    protected $table = 'eye_colors';
    protected $guarded = [];
}
