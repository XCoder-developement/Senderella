<?php

namespace App\Models\Question;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory,Translatable;
    protected $guarded=[];
    protected $table = 'questions';
    public $translatedAttributes = ['question','answer'];
    protected $translationForeignKey = 'question_id';
}
