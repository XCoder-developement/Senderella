<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\HijibTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HijibType\StoreRequest;
use App\Http\Requests\Admin\HijibType\UpdateRequest;
use App\Models\HijibType\HijibType;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HijibTypeController extends Controller
{
    protected $view = 'admin_dashboard.hijib_types.';
    protected $route = 'hijib_types.';



    public function index(HijibTypeDataTable $dataTable)
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


        HijibType::create($data);
        
        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $hijib_type = HijibType::whereId($id)->first();

        return view($this->view . 'edit' , compact('hijib_type'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $hijib_type = HijibType::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $hijib_type->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $hijib_type = HijibType::whereId($id)->firstOrFail();
        $hijib_type->delete();
        return response()->json(['status' => true]);
    }
}
