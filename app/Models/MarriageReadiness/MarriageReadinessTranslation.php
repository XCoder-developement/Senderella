<?php

namespace App\Models\MarriageReadiness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarriageReadinessTranslation extends Model
{
    use HasFactory;
    protected $table ='marriage_readiness_translations';
    protected $fillable = ['title'];
}
