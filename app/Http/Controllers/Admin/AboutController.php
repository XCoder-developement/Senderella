<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\About\UpdateRequest;
use App\Models\About\About;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AboutController extends Controller
{
    protected $view = 'admin_dashboard.abouts.';
    protected $route = 'abouts.';

    public function index()
    {
        $about = About::firstOrNew();
        return view($this->view . 'index', compact('about'));
    }


    public function update(UpdateRequest $request)
    {
        $about = about::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $about->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
