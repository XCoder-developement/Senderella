<?php

namespace App\Models\BlockReason;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockReason extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    public $translationForeignKey='block_reason_id';
    public $table = 'block_reasons';
    protected $guarded =[];
}
