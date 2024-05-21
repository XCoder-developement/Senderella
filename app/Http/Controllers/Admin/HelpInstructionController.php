<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HelpInstruction\UpdateRequest;
use App\Models\HelpInstruction\HelpInstruction;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HelpInstructionController extends Controller
{
    //
    protected $view = 'admin_dashboard.help_instructions.';
    protected $route = 'help_instructions.';

    public function index()
    {
        $help_instruction = HelpInstruction::firstOrNew();
        return view($this->view . 'index', compact('help_instruction'));
    }


    public function update(UpdateRequest $request)
    {
        $help_instruction = HelpInstruction::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $help_instruction->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
