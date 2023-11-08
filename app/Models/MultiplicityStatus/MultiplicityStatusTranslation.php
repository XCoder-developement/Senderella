<?php

namespace App\Models\MultiplicityStatus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplicityStatusTranslation extends Model
{
    use HasFactory;
    protected $table='multiplicity_status_translations';
    protected $fillable=['title'];
}
