<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\EyeColorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EyeColor\StoreRequest;
use App\Http\Requests\Admin\EyeColor\UpdateRequest;
use App\Models\EyeColor\EyeColor;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EyeColorController extends Controller
{
    protected $view = 'admin_dashboard.eye_colors.';
    protected $route = 'eye_colors.';


    public function index(EyeColorDataTable $dataTable)
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


        EyeColor::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $eye_color = EyeColor::whereId($id)->first();

        return view($this->view . 'edit' , compact('eye_color'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $eye_color = EyeColor::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $eye_color->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $eye_color = EyeColor::whereId($id)->firstOrFail();
        $eye_color->delete();
        return response()->json(['status' => true]);
    }
}
