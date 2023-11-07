<?php

namespace App\Models\HijibType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HijibTypeTranslation extends Model
{
    use HasFactory;
    protected $table = 'hijib_type_translations';
    protected $fillable =  ['title'];
}
