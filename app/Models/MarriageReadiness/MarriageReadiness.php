<?php

namespace App\Models\MarriageReadiness;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarriageReadiness extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'marriage_readiness_id';
    protected $table='marriage_readinesses';
    protected $guarded =[];
}
