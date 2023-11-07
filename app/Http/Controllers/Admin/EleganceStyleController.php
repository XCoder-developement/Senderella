<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\EleganceStyleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EleganceStyle\StoreRequest;
use App\Http\Requests\Admin\EleganceStyle\UpdateRequest;
use App\Models\EleganceStyle\EleganceStyle;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EleganceStyleController extends Controller
{
    //
    protected $view = 'admin_dashboard.elegance_styles.';
    protected $route = 'elegance_styles.';

    public function index(EleganceStyleDataTable $dataTable)
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


        EleganceStyle::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $elegance_style = EleganceStyle::whereId($id)->first();

        return view($this->view . 'edit' , compact('elegance_style'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $elegance_style = EleganceStyle::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $elegance_style->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $elegance_style = EleganceStyle::whereId($id)->firstOrFail();
        $elegance_style->delete();
        return response()->json(['status' => true]);
    }
}


