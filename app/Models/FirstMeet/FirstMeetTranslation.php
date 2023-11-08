<?php

namespace App\Models\FirstMeet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstMeetTranslation extends Model
{
    use HasFactory;
    protected $table = 'first_meet_translations';
    protected $fillable = ['title'];
}
