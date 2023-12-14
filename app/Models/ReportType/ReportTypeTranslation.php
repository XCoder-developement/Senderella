<?php

namespace App\Models\ReportType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTypeTranslation extends Model
{
    use HasFactory;
    protected $table = 'report_type_translations';
    protected $fillable =  ['title'];
}
