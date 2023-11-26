<?php

namespace App\DataTables\Admin;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportDataTable extends DataTable
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
            ->addColumn('action', 'admin_dashboard.reports.action')
            ->editColumn('report_type', function ($query) {
                return $query->report_type?->title ?? "";
            })
            ->rawColumns([
                'action',
            ]);
    }


    public function query(report $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id","desc");
    }

    
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

            ["data" => "report_type" ,"title" => __('messages.report_type'),'orderable'=>false],
            ["data" => "comment" ,"title" => __('messages.comment'),'orderable'=>false],

       ];
    }


    protected function filename(): string
    {
        return 'report_' . date('YmdHis');
    }
}
