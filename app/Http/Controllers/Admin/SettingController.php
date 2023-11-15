<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use App\Http\Controllers\Controller;
use App\DataTables\Admin\SettingDataTable;
use App\Http\Requests\Admin\Setting\SettingRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingController extends Controller
{
    protected $view = 'admin_dashboard.settings.';
    protected $route = 'settings.';
    public function index()
    {
        $setting = Setting::firstOrNew();
        return view($this->view . 'index', compact('setting'));
    }


    public function update(SettingRequest $request)
    {
        $setting = Setting::firstOrCreate();

        $data = [
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'tikTok' => $request->tikTok,
            'messenger' => $request->messenger,
            'whatsApp' => $request->whatsApp,
            'phone' => $request->phone,
            'email' => $request->email,
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'title' => $request['title-' . $localeCode],
            ];
        }
        $setting->update($data);
        return redirect()->back()->with(['success' => __("messages.editmessage")]);
    }
}
