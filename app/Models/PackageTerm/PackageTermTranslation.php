<?php

namespace App\Models\PackageTerm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTermTranslation extends Model
{
    use HasFactory;
    protected $table = 'package_term_translations';
    protected $guarded =   [];
}
