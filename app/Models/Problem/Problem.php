<?php

namespace App\Models\Problem;

use App\Models\ProblemType\ProblemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Problem extends Model
{
    use HasFactory;
    protected $table = 'problems';
    protected $guarded = [];

    public function problem_type(): BelongsTo
    {
        return $this->belongsTo(ProblemType::class);
    }
}
