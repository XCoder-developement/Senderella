<?php

namespace App\Models\Term;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'term_id';
    protected $table = 'terms';
    protected $guarded = [];

}
