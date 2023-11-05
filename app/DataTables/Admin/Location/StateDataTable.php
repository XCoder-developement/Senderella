<?php

namespace App\DataTables\Admin\Location;

use App\Models\Location\State\State;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class StateDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable($query, Request $request)
    {
        return datatables()
        ->eloquent($query)

        ->addColumn('action', 'admin_dashboard.location.states.action')
        ->rawColumns([

            'action',
        ])->filter(function ($query) use ($request) {
            if ($request->has('search') && isset($request->input('search')['value'])
            && !empty($request->input('search')['value'])) {
                $searchValue = $request->input('search')['value'];
                $query->whereTranslationLike("title", "%" . $searchValue . "%")->orWhereHas("country",function($q) use ($searchValue){
                    $q->whereTranslationLike("title", "%" . $searchValue . "%");
                });
            }
        })->orderColumn('title', function ($query, $order) {
            $query->orderByTranslation('title', $order);
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\State $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(State $model)
    {
        return $model->newQuery()->orderBy("id", "desc");
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
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
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            ["data" => "title" ,"title" => __('messages.title')],
            ["data" => "country.title" ,"title" => __('messages.country'),'orderable'=>false],
            ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
          ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'State_' . date('YmdHis');
    }
}
