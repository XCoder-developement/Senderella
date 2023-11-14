<?php

namespace App\Models\BlockReason;

use App\Models\User\UserBlock;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlockReason extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    public $translationForeignKey='block_reason_id';
    public $table = 'block_reasons';
    protected $guarded =[];


    public function user_blocks(): BelongsToMany
    {
        return $this->belongsToMany(UserBlock::class ,'user_block_reasons' ,'block_reason_id','user_block_id');
    }
}
