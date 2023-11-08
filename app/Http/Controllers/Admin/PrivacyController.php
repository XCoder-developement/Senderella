<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Privacy\UpdateRequest;
use App\Models\Privacy\Privacy;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PrivacyController extends Controller
{
    protected $view = 'admin_dashboard.privacies.';
    protected $route = 'privacies.';

    public function index()
    {
        $privacy = Privacy::firstOrNew();
        return view($this->view . 'index', compact('privacy'));
    }


    public function update(UpdateRequest $request)
    {
        $privacy = Privacy::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $privacy->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
