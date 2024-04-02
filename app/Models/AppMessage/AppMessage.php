<?php

namespace App\Models\AppMessage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMessage extends Model
{
    use HasFactory;

    protected $table = 'app_messages';
    protected $guarded = [];
}
