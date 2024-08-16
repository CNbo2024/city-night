<?php

namespace App\DataTables;

use App\Models\Inclusion;
use Yajra\DataTables\Services\DataTable;

class InclusionDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($propertyType) {

                $edit = '<a href="' . url('admin/experience/edit_inclusion/' . $propertyType->id) . '" class="btn btn-xs svbtn"><i class="fa fa-pencil"></i></a>';
                $delete = '<a href="' . url('admin/experience/delete_inclusion/' . $propertyType->id) . '" class="btn btn-xs svbtn delete-warning"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function query()
    {
        $query = Inclusion::where('lang', 'en')->where('deleted_status', 'No')->select();
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
             ->addColumn(['data' => 'name', 'name' => 'inclusion.name', 'title' => 'Name'])
             ->addColumn(['data' => 'status', 'name' => 'inclusion.status', 'title' => 'Status'])
             ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
             ->parameters([
                'dom' 	     => 'lBfrtip',
                'buttons'    => [],
                'order'      => [0, 'desc'],
                'pageLength' => \Session::get('row_per_page'),
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'propertytypedatatables_' . time();
    }
}
