<?php

namespace App\Models\Report;

use App\Models\ReportType\ReportType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'report_id';
    protected $table = 'reports';
    protected $guarded = [];

    public function points(): HasMany{
        return $this->hasMany(ReportType::class);

    }
}
