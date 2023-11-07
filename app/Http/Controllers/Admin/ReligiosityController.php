<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ReligiosityDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Religiosity\StoreRequest;
use App\Http\Requests\Admin\Religiosity\UpdateRequest;
use App\Models\Religiosity\Religiosity;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class ReligiosityController extends Controller
{
    protected $view = 'admin_dashboard.religiositys.';
    protected $route = 'religiositys.';



    public function index(ReligiosityDataTable $dataTable)
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


        Religiosity::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $religiosity = Religiosity::whereId($id)->first();

        return view($this->view . 'edit' , compact('religiosity'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $Religiosity = Religiosity::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $Religiosity ->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $religiosity = Religiosity::whereId($id)->firstOrFail();
        $religiosity->delete();
        return response()->json(['status' => true]);
    }
}
