<?php

namespace App\Models\FirstMeet;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstMeet extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'first_meet_id';
    protected $table = 'first_meets';
    protected $guarded = [];
}
