<?php

namespace App\Models\EleganceStyle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleganceStyleTranslation extends Model
{
    use HasFactory;
    protected $table = 'elegance_style_translations';
    protected $fillable =  ['title'];
}

