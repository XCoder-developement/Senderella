<?php

namespace App\DataTables\Admin;

use App\Models\Comment\Comment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CommentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin_dashboard.comments.action')
            ->addColumn('created_at', function ($post) {
                return $post->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('owner_name', function ($comment) {
                return $comment->user?->name ?? '';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Comment $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id", "desc")->where('post_id',$this->id);
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
                    [10, 25, 50, -1],
                    [10, 25, 50, 'all record']
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
            ["data" => "comment", "title" => __('messages.comment'), 'orderable' => false],
            ["data" => "owner_name", "title" => __('messages.comment_created'), 'orderable' => false],
            ["data" => "created_at", "title" => __('messages.comment_time'), 'orderable' => false],
            ['data' => 'action', 'title' => __("messages.actions"), 'printable' => false, 'exportable' => false, 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Comment_' . date('YmdHis');
    }
}
