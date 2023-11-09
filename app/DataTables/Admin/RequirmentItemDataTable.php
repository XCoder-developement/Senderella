<?php

namespace App\DataTables\Admin;

use App\Models\RequirmentItem\RequirmentItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RequirmentItemDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return datatables()
        ->eloquent($query)
        ->addColumn('action', 'admin_dashboard.requirment_items.action')
        ->rawColumns([
            'action',
        ])->editColumn('requirment', function ($query) {
            return $query->requirment?->title ?? "";
        })
        ->filter(function ($query) use ($request) {
            if ($request->has('search') && isset($request->input('search')['value'])
            && !empty($request->input('search')['value'])) {
                $searchValue = $request->input('search')['value'];
                $query->whereTranslationLike("title", "%" . $searchValue . "%");
            }
        })->orderColumn('title', function ($query, $order) {
            $query->orderByTranslation('title', $order);
        });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(RequirmentItem $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id", "desc")->where('requirment_id',$this->id);
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
            ["data" => "title" ,"title" => __('messages.title'),'orderable'=>false],
            ["data" => "requirment" ,"title" => __('messages.requirment'),'orderable'=>false],
            ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
       ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RequirmentItem_' . date('YmdHis');
    }
}
