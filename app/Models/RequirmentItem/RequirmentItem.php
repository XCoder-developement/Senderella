<?php

namespace App\Models\RequirmentItem;

use App\Models\Question\Question;
use App\Models\Requirment\Requirment;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequirmentItem extends Model
{
    use HasFactory,Translatable;
    protected $guarded=[];
    protected $table = 'requirment_items';
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'requirment_item_id';

    public function requirment(): BelongsTo
    {
        return $this->belongsTo(Requirment::class);
    }
}
