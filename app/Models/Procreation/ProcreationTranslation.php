<?php

namespace App\Models\Procreation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcreationTranslation extends Model
{
    use HasFactory;
    protected $table = 'procreation_translations';
    protected $fillable=['title'];
}
