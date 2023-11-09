<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\EducationTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EducationType\StoreRequest;
use App\Http\Requests\Admin\EducationType\UpdateRequest;
use App\Models\EducationType\EducationType;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EducationTypeController extends Controller
{
    protected $view = 'admin_dashboard.education_types.';
    protected $route = 'education_types.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:education_types-create'])->only('create');
    //     $this->middleware(['permission:education_types-read'])->only('index');
    //     $this->middleware(['permission:education_types-update'])->only('edit');
    //     $this->middleware(['permission:education_types-delete'])->only('destroy');
    // }

    public function index(EducationTypeDataTable $dataTable)
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


        EducationType::create($data);



        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $education_type = EducationType::whereId($id)->first();

        return view($this->view . 'edit' , compact('education_type'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $education_type = EducationType::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $education_type->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $education_type = EducationType::whereId($id)->firstOrFail();
        $education_type->delete();
        return response()->json(['status' => true]);
    }
}
