<?php

namespace App\Models\Requirment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirmentTranslation extends Model
{
    use HasFactory;
    protected $fillable=['title'];
    protected $table = 'requirment_translations';
}
