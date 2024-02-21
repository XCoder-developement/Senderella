<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreRequest;
use App\Http\Requests\Admin\Banner\UpdateRequest;
use App\Models\Banner\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    protected $view = 'admin_dashboard.banners.';
    protected $route = 'banners.';

    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }


    public function create()
    {
        return view($this->view . 'create');
    }

    public function store(StoreRequest $request)
    {

        if ($request->hasFile('image')) {
            $data["image"] = upload_image($request->file('image'), "banners");
        }

        $data["link"] = $request->link;
        // $data = $request->validated();
        Banner::create($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view($this->view . 'edit', compact('banner'));
    }


    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $banner = Banner::whereId($id)->first();

        $data["link"] = $request->link;

        if ($request->hasFile('image')) {
            delete_image($banner->image);
            $data["image"] = upload_image($request->image, "banners");
        }


        $banner->update($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['status' => false]);
        }

        delete_image($banner->image);

        $banner->delete();

        return response()->json(['status' => true]);
    }
}
