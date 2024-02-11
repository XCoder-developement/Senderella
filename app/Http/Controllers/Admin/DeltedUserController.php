<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\DeltedUserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeltedUserController extends Controller
{
    //
    protected $view = 'admin_dashboard.delted_users.';
    protected $route = 'delted_users.';


    public function index(DeltedUserDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }
}
