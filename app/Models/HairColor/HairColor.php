<?php

namespace App\Models\HairColor;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairColor extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'hair_color_id';
    protected $table = 'hair_colors';
    protected $guarded = [];
}
