<?php

namespace App\Models\UserQuestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswerTranslation extends Model
{
    use HasFactory;
    protected $table = 'user_answer_translations';
    protected $fillable = ['title'];
}
