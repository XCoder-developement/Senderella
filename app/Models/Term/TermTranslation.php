<?php

namespace App\Models\Term;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermTranslation extends Model
{
    use HasFactory;
    protected $table = 'term_translations';
    protected $fillable =   ['text'];
}
