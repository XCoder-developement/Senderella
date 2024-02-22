<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TextBannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TextBanner\StoreRequest;
use App\Http\Requests\Admin\TextBanner\UpdateRequest;
use App\Models\TextBanner\TextBanner;
use Illuminate\Http\Request;

class TextBannerController extends Controller
{
    //
    protected $view = 'admin_dashboard.text_banners.';
    protected $route = 'text_banners.';

    public function index(TextBannerDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }


    public function create()
    {
        return view($this->view . 'create');
    }

    public function store(StoreRequest $request)
    {



        $data["text"] = $request->link;
        // $data = $request->validated();
        TextBanner::create($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.createmessage")]);
    }


    public function edit($id)
    {
        $text_banner = TextBanner::findOrFail($id);

        return view($this->view . 'edit', compact('text_banner'));
    }


    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $text_banner = TextBanner::whereId($id)->first();

        $data["link"] = $request->link;

        if ($request->hasFile('image')) {
            delete_image($text_banner->image);
            $data["image"] = upload_image($request->image, "text_banners");
        }


        $text_banner->update($data);

        return redirect()->route($this->route . "index")
            ->with(['success' => __("messages.editmessage")]);
    }

    public function destroy($id)
    {
        $text_banner = TextBanner::find($id);

        if (!$text_banner) {
            return response()->json(['status' => false]);
        }

        delete_image($text_banner->image);

        $text_banner->delete();

        return response()->json(['status' => true]);
    }
}
