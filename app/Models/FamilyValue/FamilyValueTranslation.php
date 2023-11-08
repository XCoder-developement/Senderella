<?php

namespace App\Models\FamilyValue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyValueTranslation extends Model
{
    use HasFactory;
    protected $table ='family_value_translations';
    protected $fillable = ['title'];
}
