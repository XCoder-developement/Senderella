<?php

namespace App\Models\Notification;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory,Translatable;

    public $translatedAttributes = ['title', 'body'];

    protected $translationForeignKey = 'notification_id';
    protected $fillable = ['title','body'];
    protected $table = 'notifications';
    protected $guarded =[];

}
