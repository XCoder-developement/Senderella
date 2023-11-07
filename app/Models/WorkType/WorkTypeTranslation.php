<?php

namespace App\Models\WorkType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTypeTranslation extends Model
{
    use HasFactory;
    protected $table = 'work_type_translations';
    protected $fillable = ['title'];
}
