<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlockReason extends Model
{
    use HasFactory;
    protected $table = 'user_block_reasons';
    protected $guarded = [];
}
