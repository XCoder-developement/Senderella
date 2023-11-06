<?php

namespace App\Models\MaritalStatus;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'marital_status_id';
    protected $table = 'marital_statuses';
    protected $guarded = [];
}
