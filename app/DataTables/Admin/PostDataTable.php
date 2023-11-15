<?php

namespace App\DataTables\Admin;

use App\Models\Post\Post;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PostDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin_dashboard.posts.action')
            // ->addColumn('status', function ($post) {
            //     return $post->status ? __('messages.post_active') : __('messages.post_inactive');
            // })
            ->rawColumns([
                'action',
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Post $model): QueryBuilder
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
                    [10, 25, 50, -1],[10, 25, 50, 'all record']
                ],
                'buttons' => ['export'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ["data" => "post", "title" => __('messages.post'), 'orderable' => false],
            // ["data" => "status", "title" => __('messages.post_status'), 'orderable' => false],
            ['data' => 'action', 'title' => __("messages.actions"), 'printable' => false, 'exportable' => false, 'orderable' => false, 'searchable' => false],

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Post_' . date('YmdHis');
    }
}
