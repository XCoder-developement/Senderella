<?php

namespace App\Models\User;

use App\Models\BlockReason\BlockReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBlock extends Model
{
    use HasFactory;
    protected $table = 'user_blocks';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class , 'partner_id');
    }

    public function block_reason(): BelongsTo
    {
        return $this->belongsTo(BlockReason::class , 'block_reason_id');
    }
}
