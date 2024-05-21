<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpInstruction\HelpInstruction;
use App\Models\UseMethod\UseMethod;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class HelpInstructionController extends Controller
{
    //
    use ApiTrait;

    public function fetch_help_instructions()
    {
        try {


            $help_instruction = HelpInstruction::firstOrNew();

            $use_method = UseMethod::firstOrNew();

            //response
            $data = [
                'help_instruction'  => $help_instruction,
                'use_method'        => $use_method
            ];

            $msg = __('messages.fetch_help_instructions_and_use_methods');

            return $this->dataResponse($msg, $data, 200);
        } catch (\Exception $ex) {
            return $this->returnException($ex->getMessage(), 500);
        }
    }
}
