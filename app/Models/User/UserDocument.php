<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDocument extends Model
{
    use HasFactory;
    protected $table = 'user_documents';
    protected $guarded = [];
    protected $appends =['image_link'];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset($this->image) : '';
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
