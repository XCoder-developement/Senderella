<?php

namespace App\Models\Location\State;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateTranslation extends Model
{
    use HasFactory;
    protected $table = 'state_translations';
    protected $fillable =  ['title'];
}
