<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\FamilyValueDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FamilyValue\StoreRequest;
use App\Http\Requests\Admin\FamilyValue\UpdateRequest;
use App\Models\FamilyValue\FamilyValue;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FamilyValueController extends Controller
{
    protected $view = 'admin_dashboard.family_values.';
    protected $route = 'family_values.';



    public function index(FamilyValueDataTable $dataTable)
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


        FamilyValue::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $family_value = FamilyValue::whereId($id)->first();

        return view($this->view . 'edit' , compact('family_value'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $family_value = FamilyValue::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $family_value->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $family_value = FamilyValue::whereId($id)->firstOrFail();
        $family_value->delete();
        return response()->json(['status' => true]);
    }
}
