<?php

namespace App\Models\MaritalStatus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatusTranslation extends Model
{
    use HasFactory;
    protected $table = 'marital_status_translations';
    protected $fillable =  ['title'];
}
