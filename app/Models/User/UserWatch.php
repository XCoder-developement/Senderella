<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWatch extends Model
{
    use HasFactory;

    public $table='user_watches';
    public $guarded = [];

}
