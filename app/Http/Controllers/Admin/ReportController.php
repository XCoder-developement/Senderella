<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ReportDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    protected $view = 'admin_dashboard.reports.';
    protected $route = 'reports.';

    public function index(ReportDataTable $dataTable)
    {
        return $dataTable->render($this->view.'index');
    }
}
