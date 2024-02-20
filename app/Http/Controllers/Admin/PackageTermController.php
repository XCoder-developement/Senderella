<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageTerm\UpdateRequest;
use App\Models\PackageTerm\PackageTerm;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PackageTermController extends Controller
{
    //
    protected $view = 'admin_dashboard.package_terms.';
    protected $route = 'package_terms.';

    public function index(){
        $package_term = PackageTerm::firstOrNew();
        return view($this->view . 'index', compact('package_term'));
    }

    public function update(UpdateRequest $request)
    {
        $package_term = PackageTerm::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $package_term->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
