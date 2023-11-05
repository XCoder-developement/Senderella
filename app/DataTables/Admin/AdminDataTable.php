<?php

namespace App\DataTables\Admin;

use App\Models\Admin;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn("image",function($query){
                if($query->image_link){
                $image = $query->image_link;
                $status = '<img src="'.$image.'">';
                }else{
                    $status =__('messages.admin doesnt have image');
                }
                return $status;
            })
            ->addColumn('action', 'admin_dashboard.admins.action')
            ->rawColumns([
                'image',
                'action',
            ]);
    }

    
    public function query(Admin $model)
    {
        return $model->newQuery()->orderBy("id","desc");
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
         ["data" => "image" ,"title" => __('messages.image'),'searchable'=>false],
          ["data" => "name" ,"title" => __('messages.name')],
          ["data" => "phone" ,"title" => __('messages.phone')],
          ['data'=>'action','title'=>__("messages.actions"),'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() :string
    {
        return 'Admin_' . date('YmdHis');
    }
}
