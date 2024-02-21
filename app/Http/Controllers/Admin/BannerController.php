<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreRequest;
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
            $data["image"] = upload_image($request->file('image'), "home_banners");
        }

        $data["link"] = $request->link;
        // $data = $request->validated();
        HomeBanner::create($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $home_banner = HomeBanner::findOrFail($id);

        return view($this->view . 'edit', compact('home_banner'));
    }


    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $home_banner = HomeBanner::whereId($id)->first();

        $data["link"] = $request->link;

        if ($request->hasFile('image')) {
            delete_image($home_banner->image);
            $data["image"] = upload_image($request->image, "home_banners");
        }


        $home_banner->update($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }

    public function destroy($id)
    {
        $home_banner = HomeBanner::find($id);

        if (!$home_banner) {
            return response()->json(['status' => false]);
        }

        delete_image($home_banner->image);

        $home_banner->delete();

        return response()->json(['status' => true]);
    }
}
