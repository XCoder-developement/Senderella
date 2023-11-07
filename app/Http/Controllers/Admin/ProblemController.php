<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ProblemDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    protected $view = 'admin_dashboard.problems.';
    protected $route = 'problems.';

    // public function __construct()
    // {
    //     $this->middleware(['permission:problem_types-create'])->only('create');
    //     $this->middleware(['permission:problem_types-read'])->only('index');
    //     $this->middleware(['permission:problem_types-update'])->only('edit');
    //     $this->middleware(['permission:problem_types-delete'])->only('destroy');
    // }

    public function index(ProblemDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index');
    }
}
