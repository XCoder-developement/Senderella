<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UseMethod\UpdateRequest;
use App\Models\UseMethod\UseMethod;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UseMethodController extends Controller
{
    //
    protected $view = 'admin_dashboard.use_methods.';
    protected $route = 'use_methods.';

    public function index()
    {
        $use_method = UseMethod::firstOrNew();
        return view($this->view . 'index', compact('use_method'));
    }


    public function update(UpdateRequest $request)
    {
        $use_method = UseMethod::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $use_method->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
