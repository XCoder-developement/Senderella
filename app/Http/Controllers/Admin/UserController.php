<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $view = 'admin_dashboard.users.';
    protected $route = 'users.';


    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }

    public function destroy($id)
    {
        $user = User::whereId($id)->first();

        delete_image($user->image);

        $user->delete();
        return response()->json(['status' => true]);
    }

    public function active($id)
    {
        //activate the user
        $user = User::whereId($id)->first();
        if ($user->trusted == 0) {
            $user->update(['trusted' => 1]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
