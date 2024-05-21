<?php

namespace App\Models\HelpInstruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpInstructionTranslation extends Model
{
    use HasFactory;
    protected $table = 'help_instruction_translations';
    protected $guarded = [];
}
