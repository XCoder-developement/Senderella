<?php

namespace App\Models\Banner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table='banners';
    protected $guarded =[];
    protected $appends = ["image_link"];

    public function getImageLinkAttribute()
    {
        return  $this->image ? asset($this->image) : '';
    }
}
