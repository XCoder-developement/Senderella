<?php

namespace App\Models\ProblemType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemTypeTranslation extends Model
{
    use HasFactory;
    protected $table = 'problem_type_translations';
    protected $fillable =  ['title'];
}
