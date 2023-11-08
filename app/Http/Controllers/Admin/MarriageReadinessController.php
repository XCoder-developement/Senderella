<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MarriageReadinessDataTable;
use App\Http\Requests\Admin\MarriageReadiness\StoreRequest;
use App\Http\Requests\Admin\MarriageReadiness\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\MarriageReadiness\MarriageReadiness;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MarriageReadinessController extends Controller
{
    protected $view = 'admin_dashboard.marriage_readinesses.';
    protected $route = 'marriage_readinesses.';

    public function index(MarriageReadinessDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }


    public function create()
    {
        return view($this->view . 'create');

    }


    public function store(StoreRequest $request)
    {
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        MarriageReadiness::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $marriage_readiness = MarriageReadiness::whereId($id)->first();

        return view($this->view . 'edit' , compact('marriage_readiness'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $MarriageReadiness = MarriageReadiness::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $MarriageReadiness->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $MarriageReadiness = MarriageReadiness::whereId($id)->firstOrFail();
        $MarriageReadiness->delete();
        return response()->json(['status' => true]);
    }
}

