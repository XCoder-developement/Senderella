<?php

namespace App\Models\EducationType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationTypeTranslation extends Model
{
    use HasFactory;
    protected $table = 'education_type_translations';
    protected $fillable =  ['title'];
}
