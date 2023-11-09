<?php

namespace App\Models\RequirmentItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirmentItemTranslation extends Model
{
    use HasFactory;
    protected $fillable=['title'];
    protected $table = 'requirment_item_translations';
}
