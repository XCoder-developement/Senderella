<?php

namespace App\DataTables\Admin;

use App\Models\User\DeltedUser ;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeltedUserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
        ->eloquent($query)
        // ->editColumn("image", function ($query) {
        //     if ($query->image_link) {
        //         $image = $query->image_link;
        //         $status = '<img src="'.$image.'">';
        //     } else {
        //         $status =__('messages.doesnt have image');
        //     }
        //     return $status;
        // })
        ->editColumn("image",function($query){
            if($query->image_link){
            $image = $query->image_link;
            $status = '<img src="'.$image.'">';
            }else{
                $status =__('messages.user_didnot_have_image');
            }
            return $status;
        })
        ->addColumn('title', function ($model) {
            return $model->delted_education_type()->first()->title ?? '';
        })
        ->addColumn('mirital', function ($model) {
            return $model->delted_marital_status()->first()->title ?? '';
        })
        ->addColumn('marriage', function ($model) {
            return $model->delted_marriage_readiness()->first()->title ?? '';
        })
        ->addColumn('color', function ($model) {
            return $model->delted_color()->first()->title ?? '';
        })
        ->editColumn('state', function ($query) {
            return $query->delted_state?->title ?? "";
        })
        ->editColumn('country', function ($query) {
            return $query->delted_country?->title ?? "";
        })
        ->editColumn('nationality', function ($query) {
            return $query->delted_nationality?->title ?? "";
        })
        ->addColumn('action', 'admin_dashboard.delteduser.action')
        ->rawColumns([
            'image',
            'action',
        ]);

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DeltedUser $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('delteduser-table')
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
            ["data" => "image" ,"title" => __('messages.image')],
            ["data" => "name" ,"title" => __('messages.name')],
            ["data" => "email" ,"title" => __('messages.email'),'orderable'=>false,'searchable'=>false],
            ["data" => "phone" ,"title" => __('messages.phone')],
            ["data" => "country" ,"title" => __('messages.country')],
            ["data" => "state" ,"title" => __('messages.state')],
            ["data" => "country" ,"title" => __('messages.nationality')],
            // ["data" => "weight" ,"title" => __('messages.weight')],
            // ["data" => "height" ,"title" => __('messages.height')],
            ["data" => "title" ,"title" => __('messages.education_type')],
            ["data" => "mirital" ,"title" => __('messages.mirital_status')],
            ["data" => "marriage" ,"title" => __('messages.marriage_readiness')],
            ["data" => "about_me" ,"title" => __('messages.about')],

            // ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DeltedUser_' . date('YmdHis');
    }
}
