<?php

namespace App\DataTables\Admin;

use App\Models\Problem\Problem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProblemDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'admin_dashboard.problems.action')
            ->editColumn('problem_type', function ($query) {
                return $query->problem_type?->title ?? "";
            })
            ->rawColumns([
                'action',
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Problem $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id","desc");
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->parameters([
            'dom' => 'Blfrtip',
            'order' => [0, 'desc'],
            'lengthMenu' => [
                [10,25,50,-1],[10,25,50,'all record']
            ],
       'buttons'      => ['export'],
   ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ["data" => "email" ,"title" => __('messages.email'),'orderable'=>false],
            ["data" => "problem_type" ,"title" => __('messages.problem_type'),'orderable'=>false],
            ["data" => "comment" ,"title" => __('messages.comment'),'orderable'=>false],
            ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
       ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Problem_' . date('YmdHis');
    }
}
