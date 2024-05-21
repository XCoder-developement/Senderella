<?php

namespace App\Models\HelpInstruction;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpInstruction extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes = ['text'];
    protected $translationForeignKey = 'help_instruction_id';
    protected $table = 'help_instructions';
    protected $guarded = [];
}
