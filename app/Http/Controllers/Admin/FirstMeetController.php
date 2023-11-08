<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\FirstMeetDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FirstMeet\StoreRequest;
use App\Http\Requests\Admin\FirstMeet\UpdateRequest;
use App\Models\FirstMeet\FirstMeet;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FirstMeetController extends Controller
{
    protected $view = 'admin_dashboard.first_meets.';
    protected $route = 'first_meets.';



    public function index(FirstMeetDataTable $dataTable)
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


        FirstMeet::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $first_meet = FirstMeet::whereId($id)->first();

        return view($this->view . 'edit' , compact('first_meet'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $first_meet = FirstMeet::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $first_meet->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $first_meet = FirstMeet::whereId($id)->firstOrFail();
        $first_meet->delete();
        return response()->json(['status' => true]);
    }
}
