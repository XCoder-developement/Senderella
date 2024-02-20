<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRule\UpdateRequest;
use App\Models\Package\Package;
use App\Models\PackageRule\PackageRule;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PackageRuleController extends Controller
{
    //
    protected $view = 'admin_dashboard.package_rules.';
    protected $route = 'package_rules.';

    public function index(){
        $package_rule = PackageRule::firstOrNew();
        return view($this->view . 'index', compact('package_rule'));
    }

    public function update(UpdateRequest $request)
    {
        $package_rule = PackageRule::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $package_rule->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }

}
