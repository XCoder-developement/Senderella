<?php

namespace App\Models\Report;

use App\Models\ReportType\ReportType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'report_id';
    protected $table = 'reports';
    protected $guarded = [];

    public function report_types(): BelongsToMany{
        return $this->belongsToMany(ReportType::class,'report_points','report_id','report_type_id');
    }
}
