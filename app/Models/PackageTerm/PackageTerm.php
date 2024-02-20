<?php

namespace App\Models\PackageTerm;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTerm extends Model
{
    use HasFactory , Translatable;

    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'package_term_id';
    protected $table = 'package_terms';
    protected $guarded = [];
}
