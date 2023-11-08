<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
    use HasFactory;
    protected $table = 'question_translations';
    protected $fillable = ['question','answer'];
}
