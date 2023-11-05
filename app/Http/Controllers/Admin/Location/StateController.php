<?php

namespace App\Http\Controllers\Admin\Location;

use App\DataTables\Admin\Location\StateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Location\State\StoreRequest;
use App\Http\Requests\Admin\Location\State\UpdateRequest;
use App\Models\Location\Country\Country;
use App\Models\Location\State\State;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class StateController extends Controller
{
    protected $view = 'admin_dashboard.location.states.';
    protected $route = 'states.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:states-create'])->only('create');
    //     $this->middleware(['permission:states-read'])->only('index');
    //     $this->middleware(['permission:states-update'])->only('edit');
    //     $this->middleware(['permission:states-delete'])->only('destroy');
    // }

    public function index(StateDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }


    public function create()
    {
        $countries = Country::get();
        return view($this->view . 'create' , compact('countries'));

    }


    public function store(StoreRequest $request)
    {
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode]
          ];
        }
        $data['country_id'] = $request->country_id;


        State::create($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $state = State::whereId($id)->firstOrFail();
        $countries = Country::get();
        return view($this->view . 'edit' , compact('state','countries'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $state = State::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode]
          ];
        }
        $data['country_id'] = $request->country_id;


        $state->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $state = State::whereId($id)->firstOrFail();
        $state->delete();
        return response()->json(['status' => true]);
    }

    public function filter_states(Request $request){
        $country_id=$request->country_id;
        $states = State::where('country_id',$country_id)->get();
        $data ="";
        foreach($states as $state){
            $data .= '<option value="'.$state->id.'">'.$state->title.'</option>';
        }
        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }
}
