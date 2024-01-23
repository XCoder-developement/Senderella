<?php

namespace App\Http\Controllers\Admin\Location;

use App\DataTables\Admin\Location\CountryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Location\Country\StoreRequest;
use App\Http\Requests\Admin\Location\Country\UpdateRequest;
use App\Models\Location\Country\Country;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CountryController extends Controller
{
    protected $view = 'admin_dashboard.location.countries.';
    protected $route = 'countries.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:countries-create'])->only('create');
    //     $this->middleware(['permission:countries-read'])->only('index');
    //     $this->middleware(['permission:countries-update'])->only('edit');
    //     $this->middleware(['permission:countries-delete'])->only('destroy');
    // }

    public function index(CountryDataTable $dataTable)
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

        if($request->hasFile('image')){
            $data["image"] = upload_image($request->image,"countries");
        }

        Country::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $country = Country::whereId($id)->firstOrFail();
        return view($this->view . 'edit' , compact('country'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $country = Country::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }

        if($request->hasFile('image')){
            $data["image"] = upload_image($request->image,"countries");
        }

        $country->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $country = Country::whereId($id)->firstOrFail();
        $country->delete();
        return response()->json(['status' => true]);
    }
}
