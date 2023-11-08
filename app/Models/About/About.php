<?php

namespace App\Models\About;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'about_id';
    protected $table = 'abouts';
    protected $guarded = [];
}
