<?php

namespace App\Models\ReportType;

use App\Models\Report\Report;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportType extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'report_type_id';
    protected $table = 'report_types';
    protected $guarded = [];

    public function points(): HasMany{
        return $this->hasMany(Report::class);
    }
}
