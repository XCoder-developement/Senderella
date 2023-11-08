<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MovingPlaceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MovingPlace\StoreRequest;
use App\Http\Requests\Admin\MovingPlace\UpdateRequest;
use App\Models\MovingPlace\MovingPlace;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MovingPlaceController extends Controller
{
    //
    protected $view = 'admin_dashboard.moving_places.';
    protected $route = 'moving_places.';

    public function index(MovingPlaceDataTable $dataTable)
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


        MovingPlace::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $moving_place = MovingPlace::whereId($id)->first();

        return view($this->view . 'edit' , compact('moving_place'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $moving_place = MovingPlace::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $moving_place->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $moving_place = MovingPlace::whereId($id)->firstOrFail();
        $moving_place->delete();
        return response()->json(['status' => true]);
    }
}
