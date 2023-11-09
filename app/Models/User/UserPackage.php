<?php

namespace App\Models\User;

use App\Models\Package\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPackage extends Model
{
    use HasFactory;
    protected $table = 'user_packages';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
