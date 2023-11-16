<?php

namespace App\Models\UserSearch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSearch extends Model
{
    use HasFactory;
    protected $table = 'user_searches';
    protected $guarded = [];
    public function requirments(){
        return $this->hasMany(UserSearchRequirment::class,'user_search_id','id');
    }
}
