<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PackageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Package\StoreRequest;
use App\Http\Requests\Admin\Package\UpdateRequest;
use App\Models\Package\Package;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PackageController extends Controller
{
    protected $view = 'admin_dashboard.packages.';
    protected $route = 'packages.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:packages-create'])->only('create');
    //     $this->middleware(['permission:packages-read'])->only('index');
    //     $this->middleware(['permission:packages-update'])->only('edit');
    //     $this->middleware(['permission:packages-delete'])->only('destroy');
    // }

    public function index(PackageDataTable $dataTable)
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
        $data['price'] = $request->price;
        $data['currency'] = $request->currency;

        Package::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $package = Package::whereId($id)->first();

        return view($this->view . 'edit' , compact('package'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $package = Package::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }

        $data['price'] = $request->price;
        $data['currency'] = $request->currency;

        $package->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $package = Package::whereId($id)->firstOrFail();
        $package->delete();
        return response()->json(['status' => true]);
    }
}
