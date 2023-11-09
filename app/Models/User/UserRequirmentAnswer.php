<?php

namespace App\Models\User;

use App\Models\Requirment\Requirment;
use App\Models\RequirmentItem\RequirmentItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRequirmentAnswer extends Model
{
    use HasFactory;
    protected $table = 'user_requirment_answers';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function requirment(): BelongsTo
    {
        return $this->belongsTo(Requirment::class);
    }
    public function requirment_item(): BelongsTo
    {
        return $this->belongsTo(RequirmentItem::class);
    }
}
