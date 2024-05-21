<?php

namespace App\Models\UseMethod;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UseMethod extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'use_method_id';
    protected $table = 'use_methods';
    protected $guarded = [];
}
