<?php

namespace App\Models\Package;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory,Translatable;
    protected $guarded = [];
    protected $table = 'packages';
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'package_id';

}
