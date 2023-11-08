<?php

namespace App\Models\Privacy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacyTranslation extends Model
{
    use HasFactory;
    protected $table = 'privacy_translations';
    protected $fillable =   ['text'];
}
