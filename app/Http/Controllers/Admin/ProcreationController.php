<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ProcreationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Procreation\StoreRequest;
use App\Http\Requests\Admin\Procreation\UpdateRequest;
use App\Models\Procreation\Procreation;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProcreationController extends Controller
{
    protected $view = 'admin_dashboard.procreations.';
    protected $route = 'procreations.';



    public function index(ProcreationDataTable $dataTable)
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


        Procreation::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $procreation = Procreation::whereId($id)->first();

        return view($this->view . 'edit' , compact('procreation'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $procreation = Procreation::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $procreation->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $procreation = Procreation::whereId($id)->firstOrFail();
        $procreation->delete();
        return response()->json(['status' => true]);
    }
}
