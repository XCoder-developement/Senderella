<?php

namespace App\DataTables\Admin;

use App\Models\AppMessage\AppMessage;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AppMessageDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('action', 'admin_dashboard.app_messages.action')
        ->rawColumns([
            'action',
        ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(AppMessage $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('appmessage-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ["data" => "name" ,"title" => __('messages.name'),'searchable'=>false],
            ["data" => "email" ,"title" => __('messages.email')],
            ["data" => "phone" ,"title" => __('messages.phone')],
            ["data" => "subject" ,"title" => __('messages.subject')],
            ["data" => "message" ,"title" => __('messages.message')],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AppMessage_' . date('YmdHis');
    }
}
