<?php

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLastShow extends Model
{
    use HasFactory;
    protected $table = 'user_last_shows';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEndDateFormatAttribute()
    {
        return Carbon::parse($this->end_date)->format('Y-m-d g:i A');
    }
}
