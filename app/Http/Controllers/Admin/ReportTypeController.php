<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ReportTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportType\StoreRequest;
use App\Http\Requests\Admin\ReportType\UpdateRequest;
use App\Models\ReportType\ReportType;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ReportTypeController extends Controller
{
    protected $view = 'admin_dashboard.reports.';
    protected $route = 'reports.';


    public function index(ReportTypeDataTable $dataTable)
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


        ReportType::create($data);



        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $report = ReportType::whereId($id)->first();

        return view($this->view . 'edit' , compact('report'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $report = ReportType::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $report->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $report = ReportType::whereId($id)->firstOrFail();
        $report->delete();
        return response()->json(['status' => true]);
    }
}
