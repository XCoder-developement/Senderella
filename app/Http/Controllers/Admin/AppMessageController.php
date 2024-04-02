<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AppMessageDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppMessageController extends Controller
{
    //
    protected $view = 'admin_dashboard.app_messages.';
    protected $route = 'app_messages.';

    public function index(AppMessageDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }

}
