<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\WorkTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkType\StoreRequest;
use App\Http\Requests\Admin\WorkType\UpdateRequest;
use App\Models\WorkType\WorkType;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class WorkTypeController extends Controller
{
    protected $view = 'admin_dashboard.work_types.';
    protected $route = 'work_types.';



    public function index(WorkTypeDataTable $dataTable)
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


        WorkType::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $work_type = WorkType::whereId($id)->first();

        return view($this->view . 'edit' , compact('work_type'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $work_type = WorkType::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $work_type->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $work_type = WorkType::whereId($id)->firstOrFail();
        $work_type->delete();
        return response()->json(['status' => true]);
    }
}
