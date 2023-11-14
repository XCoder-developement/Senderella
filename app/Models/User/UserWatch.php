<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWatch extends Model
{
    use HasFactory;

    public $table='user_watches';
    public $guarded = [];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class , 'partner_id');
    }
}
