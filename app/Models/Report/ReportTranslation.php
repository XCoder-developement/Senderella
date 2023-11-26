<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTranslation extends Model
{
    use HasFactory;
    protected $table = 'report_translations';
    protected $fillable =  ['title'];
}
