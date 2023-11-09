<?php

namespace App\Models\Requirment;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirment extends Model
{
    use HasFactory,Translatable;
    protected $guarded=[];
    protected $table = 'requirments';
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'requirment_id';
}
