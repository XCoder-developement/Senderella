<?php

namespace App\Http\Controllers\admin;

use App\DataTables\Admin\RequirmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Requirment\UpdateRequest;
use App\Http\Requests\Admin\Requirment\StoreRequest;
use App\Models\Requirment\Requirment;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RequirmentController extends Controller
{
    //
    protected $view = 'admin_dashboard.requirments.';
    protected $route = 'requirments.';

    public function index(RequirmentDataTable $dataTable)
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
            $data[$localeCode] = [
                'title' => $request['title-' . $localeCode],
            ];
        }

        $data["answer_type"] = $request->answer_type;

        Requirment::create($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("message.createmessage")]);
    }
    public function edit($id)
    {

        $requirment = Requirment::whereId($id)->firstOrFail();
        return view($this->view . 'edit', compact('requirment'));
    }

    public function update(UpdateRequest $request, $id)
    {

        $requirment = Requirment::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = [
                'title' => $request['title-' . $localeCode],
            ];
        }

        $data["answer_type"] = $request->answer_type;
        // Requirment::create($data);

        $requirment->update($data);
        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages,editmessage")]);
    }
    public function destroy($id)
    {

        $requirment = Requirment::whereId($id)->firstOrFail();
        $requirment->delete();
        return response()->json(['status' => true]);
    }
}
