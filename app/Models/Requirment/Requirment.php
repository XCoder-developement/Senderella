<?php

namespace App\Models\Requirment;

use App\Models\RequirmentItem\RequirmentItem;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirment extends Model
{
    use HasFactory,Translatable;
    protected $guarded=[];
    protected $table = 'requirments';
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'requirment_id';

    public function requirment_items():HasMany{
        return $this->hasMany(RequirmentItem::class);
    }
}
