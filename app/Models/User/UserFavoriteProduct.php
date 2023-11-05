<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteProduct extends Model
{
    use HasFactory;
    protected $table = 'user_favorite_products';
    protected $guarded = [];

}
