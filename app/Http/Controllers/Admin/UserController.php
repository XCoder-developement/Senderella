<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $view = 'admin_dashboard.users.';
    protected $route = 'users.';


    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }

}
