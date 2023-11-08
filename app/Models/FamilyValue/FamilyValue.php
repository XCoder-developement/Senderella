<?php

namespace App\Models\FamilyValue;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyValue extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes =['title'];
    public $translationForeignKey = 'family_value_id';
    public $table = 'family_values';
    protected $guarded =[];
}
