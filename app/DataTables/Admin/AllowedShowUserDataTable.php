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

class AllowedShowUserDataTable extends DataTable
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
        // ->editColumn("image", function ($query) {
        //     if ($query->image_link) {
        //         $image = $query->image_link;
        //         $status = '<img src="'.$image.'">';
        //     } else {
        //         $status =__('messages.doesnt have image');
        //     }
        //     return $status;
        // })
        ->addColumn('image', function ($model) {
            $imageModel = $model->images()->where('is_primary', 1)->first();

            if ($imageModel) {
                $image = asset($imageModel->image);
                $status = '<img src="' . $image . '">';
                return $status;
            }

         else {
                    $status =__('messages.doesnt have image');
                }
                return $status;
        })
        ->addColumn('title', function ($model) {
            return $model->education_type()->first()->title ?? '';
        })
        ->addColumn('mirital', function ($model) {
            return $model->marital_status()->first()->title ?? '';
        })
        ->addColumn('marriage', function ($model) {
            return $model->marriage_readiness()->first()->title ?? '';
        })
        ->addColumn('color', function ($model) {
            return $model->color()->first()->title ?? '';
        })
        ->editColumn('state', function ($query) {
            return $query->state?->title ?? "";
        })
        ->editColumn('country', function ($query) {
            return $query->country?->title ?? "";
        })
        ->editColumn('nationality', function ($query) {
            return $query->nationality?->title ?? "";
        })
        ->addColumn('action', 'admin_dashboard.allowed_users.action')
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
            ["data" => "image" ,"title" => __('messages.image')],
            ["data" => "name" ,"title" => __('messages.name')],
            ["data" => "email" ,"title" => __('messages.email'),'orderable'=>false,'searchable'=>false],
            ["data" => "phone" ,"title" => __('messages.phone')],
            // ["data" => "country" ,"title" => __('messages.country')],
            // ["data" => "state" ,"title" => __('messages.state')],
            // ["data" => "country" ,"title" => __('messages.nationality')],
            // // ["data" => "weight" ,"title" => __('messages.weight')],
            // // ["data" => "height" ,"title" => __('messages.height')],
            // ["data" => "title" ,"title" => __('messages.education_type')],
            // ["data" => "mirital" ,"title" => __('messages.mirital_status')],
            // ["data" => "marriage" ,"title" => __('messages.marriage_readiness')],
            // ["data" => "about_me" ,"title" => __('messages.about')],

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
