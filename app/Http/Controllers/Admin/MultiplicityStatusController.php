<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MultiplicityStatusDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MultiplicityStatus\StoreRequest;
use App\Http\Requests\Admin\MultiplicityStatus\UpdateRequest;
use App\Models\MultiplicityStatus\MultiplicityStatus;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MultiplicityStatusController extends Controller
{
    protected $view = 'admin_dashboard.multiplicity_statuses.';
    protected $route = 'multiplicity_statuses.';



    public function index(MultiplicityStatusDataTable $dataTable)
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


        MultiplicityStatus::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $multiplicity_status = MultiplicityStatus::whereId($id)->first();

        return view($this->view . 'edit' , compact('multiplicity_status'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $multiplicity_status = MultiplicityStatus::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $multiplicity_status->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $multiplicity_status = MultiplicityStatus::whereId($id)->firstOrFail();
        $multiplicity_status->delete();
        return response()->json(['status' => true]);
    }
}
