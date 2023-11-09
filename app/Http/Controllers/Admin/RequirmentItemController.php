<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\RequirmentItemDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequirmentItem\StoreRequest;
use App\Http\Requests\Admin\RequirmentItem\UpdateRequest;
use App\Models\Requirment\Requirment;
use App\Models\RequirmentItem\RequirmentItem;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RequirmentItemController extends Controller
{
    protected $view = 'admin_dashboard.requirment_items.';
    protected $route = 'requirment_items.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:requirment_items-create'])->only('create');
    //     $this->middleware(['permission:requirment_items-read'])->only('index');
    //     $this->middleware(['permission:requirment_items-update'])->only('edit');
    //     $this->middleware(['permission:requirment_items-delete'])->only('destroy');
    // }

    public function index(RequirmentItemDataTable $dataTable ,$id)
    {
        $requirment = Requirment::whereId($id)->firstorFail();
        $dataTable->id =$id;
        return $dataTable->render($this->view . 'index', compact('id','requirment'));
    }


    public function create($id)
    {
        $requirment = Requirment::whereId($id)->firstOrFail();
        return view($this->view . 'create',compact('id','requirment'));

    }


    public function store(StoreRequest $request, $id)
    {
        $requirment = Requirment::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }
        $data["requirment_id"] = $requirment->id;
        RequirmentItem::create($data);

        return redirect()->route($this->route."index",["id"=>$id])
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $requirment_item = RequirmentItem::whereId($id)->firstOrFail();

        return view($this->view . 'edit',compact('requirment_item'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $requirment_item = RequirmentItem::whereId($id)->firstOrFail();

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }
        $requirment_item->update($data);


         return redirect()->route($this->route."index",["id"=>$requirment_item->requirment_id])
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $requirment_item = RequirmentItem::whereId($id)->firstOrFail();
        $requirment_item->delete();
        return response()->json(['status' => true]);
    }
}
