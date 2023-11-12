<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BlockReasonDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlockReason\StoreRequest;
use App\Http\Requests\Admin\BlockReason\UpdateRequest;
use App\Models\BlockReason\BlockReason;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BlockReasonController extends Controller
{
    protected $view = 'admin_dashboard.block_reasons.';
    protected $route = 'block_reasons.';



    public function index(BlockReasonDataTable $dataTable)
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


        BlockReason::create($data);

        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $block_reason = BlockReason::whereId($id)->first();

        return view($this->view . 'edit' , compact('block_reason'));

    }


    public function update(UpdateRequest $request, $id)
    {
        $block_reason = BlockReason::whereId($id)->firstOrFail();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $request['title-' . $localeCode],
          ];
        }


        $block_reason->update($data);


        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }


    public function destroy($id)
    {
        $block_reason = BlockReason::whereId($id)->firstOrFail();
        $block_reason->delete();
        return response()->json(['status' => true]);
    }
}
