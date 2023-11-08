<?php

namespace App\Models\Religiosity;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religiosity extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes = ['title'];

    protected $translationForeignKey = 'religiosity_id';

    protected $table = 'religiositys';

    protected $guarded = [];

}
