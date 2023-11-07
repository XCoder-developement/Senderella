<?php

namespace App\Models\HijibType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HijibType extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'hijib_type_id';
    protected $table = 'hijib_types';
    protected $guarded = [];
}
