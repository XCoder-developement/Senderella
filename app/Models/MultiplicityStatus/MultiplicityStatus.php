<?php

namespace App\Models\MultiplicityStatus;
use AssertionError\Translatable\Translatalbe;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplicityStatus extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes=['title'];
    protected $translationForeignKey = 'multiplicity_status_id';
    protected $table = 'multiplicity_statuses';
    protected $guarded=[];
}
