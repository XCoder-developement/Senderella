<?php

namespace App\DataTables\Admin;

use App\Models\Question\Question;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuestionDataTable extends DataTable
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

            ->addColumn('action', 'admin_dashboard.questions.action')
            ->rawColumns([

                'action',
            ])->filter(function ($query) use ($request) {
                if (
                    $request->has('search') && isset($request->input('search')['value'])
                    && !empty($request->input('search')['value'])
                ) {
                    $searchValue = $request->input('search')['value'];
                    $query->whereTranslationLike("question", "%" . $searchValue . "%");
                }
            })->orderColumn('question', function ($query, $order) {
                $query->orderByTranslation('question', $order);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Question $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Question $model): QueryBuilder
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
                    [10, 25, 50, -1], [10, 25, 50, 'all record']
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
            ["data" => "question", "title" => __('messages.question')],
            ["data" => "answer", "title" => __('messages.answer')],
            ['data' => 'action', 'title' => __("messages.actions"), 'printable' => false, 'exportable' => false, 'orderable' => false, 'searchable' => false],
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Question_' . date('YmdHis');
    }
}
