<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ProblemTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProblemType\StoreRequest;
use App\Http\Requests\Admin\ProblemType\UpdateRequest;
use App\Models\ProblemType\ProblemType;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProblemTypeController extends Controller
{
    protected $view = 'admin_dashboard.problem_types.';
    protected $route = 'problem_types.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:problem_types-create'])->only('create');
    //     $this->middleware(['permission:problem_types-read'])->only('index');
    //     $this->middleware(['permission:problem_types-update'])->only('edit');
    //     $this->middleware(['permission:problem_types-delete'])->only('destroy');
    // }

    public function index(ProblemTypeDataTable $dataTable)
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


        ProblemType::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $problem_type = ProblemType::whereId($id)->first();

        return view($this->view . 'edit' , compact('problem_type'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $problem_type = ProblemType::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $problem_type->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $problem_type = ProblemType::whereId($id)->firstOrFail();
        $problem_type->delete();
        return response()->json(['status' => true]);
    }
}
