<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Term\UpdateRequest;
use App\Models\Term\Term;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TermController extends Controller
{
    protected $view = 'admin_dashboard.terms.';
    protected $route = 'terms.';

    public function index()
    {
        $term = Term::firstOrNew();
        return view($this->view . 'index', compact('term'));
    }


    public function update(UpdateRequest $request)
    {
        $term = Term::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'text' => $request['text-' . $localeCode],

            ];
        }

        $term->update($data);

        return redirect()->back()
            ->with(['success' => __("messages.editmessage")]);
    }
}
