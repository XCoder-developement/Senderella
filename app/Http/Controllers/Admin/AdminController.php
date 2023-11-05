<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\DataTables\Admin\AdminDataTable;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;

class AdminController extends Controller
{

    protected $view = 'admin_dashboard.admins.';
     protected $route = 'admins.';

     public function index(AdminDataTable $dataTable)
     {
        return $dataTable->render($this->view . 'index');
     }

    public function create()
    {
        return view($this->view . 'create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = hash_user_password($request->password);

        if($request->hasFile('image')){
            $data["image"] = upload_image($request->image,"admins");
        }

       $admin = Admin::create($data);
        // $admin->syncRoles(['super_super_admin']);

        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.createmessage")]);
    }

    public function edit($id)
    {
        $admin = Admin::whereId($id)->first();
        return view($this->view . 'edit',compact("admin"));
    }


    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $admin = Admin::whereId($id)->first();

        if($request->password){
         $data['password'] = hash_user_password($request->password);
        }
        if($request->hasFile('image')){
            delete_image($admin->image);
            $data["image"] = upload_image($request->image,"admins");
        }


        $admin->update($data);

        return redirect()->route($this->route."index")
        ->with(['success'=> __("messages.editmessage")]);
    }

    public function destroy($id)
    {
        $admin = Admin::whereId($id)->first();

       delete_image($admin->image);

        $admin->delete();
        return response()->json(['status' => true]);
    }

}
