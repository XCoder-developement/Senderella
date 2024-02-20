<?php

namespace App\Models\PackageRule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRuleTranslation extends Model
{
    use HasFactory;

    protected $table = 'package_rule_translations';
    protected $guarded =   [];
}
