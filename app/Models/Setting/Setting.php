<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, Translatable;
    protected $table = 'settings';
    public $translatedAttributes = ['description'];
    public $translationForeignKey = 'setting_id';
    protected $guarded = [];


}
