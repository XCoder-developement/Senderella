<?php

namespace App\Models\Religiosity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReligiosityTranslation extends Model
{
    use HasFactory;
    protected $table = 'religiosity_translations';
    protected $fillable = ['title'];
}
