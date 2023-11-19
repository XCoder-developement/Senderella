<?php

namespace App\Models\UserSearch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSearchRequirment extends Model
{
    use HasFactory;
    protected $table = 'user_search_requirments';
    protected $guarded = [];
}
