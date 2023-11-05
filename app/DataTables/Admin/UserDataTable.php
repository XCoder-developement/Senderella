<?php

namespace App\DataTables\Admin;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
        ->editColumn("image", function ($query) {
            if ($query->image_link) {
                $image = $query->image_link;
                $status = '<img src="'.$image.'">';
            } else {
                $status =__('messages.doesnt have image');
            }
            return $status;
        })
        ->editColumn('state', function ($query) {
            return $query->state?->title ?? "";
        })
        ->editColumn('city', function ($query) {
            return $query->city?->title ?? "";
        })
        ->editColumn('zone', function ($query) {
            return $query->zone?->title ?? "";
        })
        ->addColumn('action', 'admin_dashboard.users.action')
        ->rawColumns([
            'image',
            'action',
        ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id", "desc");
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
            ["data" => "image" ,"title" => __('messages.image'),'searchable'=>false],
            ["data" => "name" ,"title" => __('messages.name')],
            ["data" => "phone" ,"title" => __('messages.phone')],
            ["data" => "state" ,"title" => __('messages.state')],
            ["data" => "city" ,"title" => __('messages.city')],
            ["data" => "zone" ,"title" => __('messages.zone')],
            // ["data" => "email" ,"title" => __('messages.email'),'orderable'=>false,'searchable'=>false],
            ["data" => "points" ,"title" => __('messages.points'),'orderable'=>false,'searchable'=>false],
            ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
