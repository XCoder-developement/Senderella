<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\HairColorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HairColor\StoreRequest;
use App\Http\Requests\Admin\HairColor\UpdateRequest;
use App\Models\HairColor\HairColor;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HairColorController extends Controller
{
    protected $view = 'admin_dashboard.hair_colors.';
    protected $route = 'hair_colors.';


    public function index(HairColorDataTable $dataTable)
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


        HairColor::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $hair_color = HairColor::whereId($id)->first();

        return view($this->view . 'edit' , compact('hair_color'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $hair_color = HairColor::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $hair_color->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $hair_color = HairColor::whereId($id)->firstOrFail();
        $hair_color->delete();
        return response()->json(['status' => true]);
    }
}
