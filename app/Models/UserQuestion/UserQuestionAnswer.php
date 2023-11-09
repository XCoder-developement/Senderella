<?php

namespace App\Models\UserQuestion;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuestionAnswer extends Model
{
    use HasFactory;
    protected $table = 'user_question_answers';
    protected $guarded=[];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user_question(): BelongsTo
    {
        return $this->belongsTo(UserQuestion::class);
    }

    public function user_answer(): BelongsTo
    {
        return $this->belongsTo(UserAnswer::class);
    }
}
