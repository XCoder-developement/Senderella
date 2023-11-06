<?php

namespace App\Http\Controllers\admin;

use App\DataTables\Admin\ColorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Color\StoreRequest;
use App\Http\Requests\Admin\Color\UpdateRequest;
use App\Models\Color\Color;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class ColorController extends Controller
{

    protected $view = 'admin_dashboard.colors.';
    protected $route = 'colors.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:colors-create'])->only('create');
    //     $this->middleware(['permission:colors-read'])->only('index');
    //     $this->middleware(['permission:colors-update'])->only('edit');
    //     $this->middleware(['permission:colors-delete'])->only('destroy');
    // }

    public function index(ColorDataTable $dataTable)
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


        Color::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $color = Color::whereId($id)->first();

        return view($this->view . 'edit' , compact('color'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $color = Color::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $color->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $color = Color::whereId($id)->firstOrFail();
        $color->delete();
        return response()->json(['status' => true]);
    }
    //
}
