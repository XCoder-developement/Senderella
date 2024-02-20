<?php

namespace App\Models\PackageRule;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRule extends Model
{
    use HasFactory , Translatable;

    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'package_rule_id';
    protected $table = 'package_rules';
    protected $guarded = [];
}
