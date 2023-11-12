<?php

namespace App\Models\BlockReason;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockReasonTranslation extends Model
{
    use HasFactory;
    public $table = 'block_reason_translations';
    protected $fillable =['title'];
}
