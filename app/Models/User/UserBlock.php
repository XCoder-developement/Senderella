<?php

namespace App\Models\User;

use App\Models\BlockReason\BlockReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function reasons(): BelongsToMany
    {
        return $this->belongsToMany(BlockReason::class ,'user_block_reasons','user_block_id' ,'block_reason_id');
    }
}
