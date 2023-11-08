<?php

namespace App\Models\HealthStatus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthStatusTranslation extends Model
{
    use HasFactory;
    protected $table = 'health_status_translations';
    protected $fillable = ['title'];
}
