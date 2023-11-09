<?php

namespace App\Models\UserQuestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuestionTranslation extends Model
{
    use HasFactory;
    protected $table = 'user_question_translations';
    protected $fillable = ['title'];
}
