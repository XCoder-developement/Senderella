<?php

namespace App\Models\EleganceStyle;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleganceStyle extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'elegance_style_id';
    protected $table = 'elegance_styles';
    protected $guarded = [];

}
