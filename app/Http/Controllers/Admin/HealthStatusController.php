<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\HealthStatusDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HealthStatus\StoreRequest;
use App\Http\Requests\Admin\HealthStatus\UpdateRequest;
use App\Models\HealthStatus\HealthStatus;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HealthStatusController extends Controller
{
    protected $view = 'admin_dashboard.health_statuss.';
    protected $route = 'health_statuss.';


    public function index(HealthStatusDataTable $dataTable)
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


        HealthStatus::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $health_status = HealthStatus::whereId($id)->first();

        return view($this->view . 'edit' , compact('health_status'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $health_status = HealthStatus::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $health_status->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $health_status = HealthStatus::whereId($id)->firstOrFail();
        $health_status->delete();
        return response()->json(['status' => true]);
    }
}
