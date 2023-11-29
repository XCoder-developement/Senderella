<?php

namespace App\Models\Points;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Points extends Model
{
    use HasFactory;
    protected $table = "report_points";
    protected $guard = [];
    public function points(){
        return $this->belongsToMany(Report::class,'report_type_id');
    }
}
