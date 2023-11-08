<?php

namespace App\Models\HealthStatus;
use Astrotomic\Translatable\Translatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthStatus extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'health_status_id';
    protected $table = 'health_statuss';
    protected $guarded = [];

}
