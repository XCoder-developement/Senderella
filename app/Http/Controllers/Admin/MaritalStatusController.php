<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MaritalStatusDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaritalStatus\StoreRequest;
use App\Http\Requests\Admin\MaritalStatus\UpdateRequest;
use App\Models\MaritalStatus\MaritalStatus;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MaritalStatusController extends Controller
{
    protected $view = 'admin_dashboard.marital_statuses.';
    protected $route = 'marital_statuses.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:marital_statuses-create'])->only('create');
    //     $this->middleware(['permission:marital_statuses-read'])->only('index');
    //     $this->middleware(['permission:marital_statuses-update'])->only('edit');
    //     $this->middleware(['permission:marital_statuses-delete'])->only('destroy');
    // }

    public function index(MaritalStatusDataTable $dataTable)
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


        MaritalStatus::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $marital_status = MaritalStatus::whereId($id)->first();

        return view($this->view . 'edit' , compact('marital_status'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $marital_status = MaritalStatus::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $marital_status->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $marital_status = MaritalStatus::whereId($id)->firstOrFail();
        $marital_status->delete();
        return response()->json(['status' => true]);
    }
}
