<?php

namespace App\Models\UserQuestion;

use App\Models\User\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAnswer extends Model
{
    use HasFactory,Translatable;
    protected $guarded=[];
    protected $table = 'user_answers';
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'user_answer_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user_question_answers(): HasMany
    {
        return $this->HasMany(UserQuestionAnswer::class);
    }
}
