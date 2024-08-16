<?php

namespace App\DataTables;

use App\Models\SpaceType;
use Yajra\DataTables\Services\DataTable;

class SpaceTypeDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($spaceType) {

                $edit = '<a href="' . url('admin/settings/edit-space-type/' . $spaceType->id) . '" class="btn btn-xs svbtn"><i class="fa fa-pencil"></i></a>';
                $delete = '<a href="' . url('admin/settings/delete-space-type/' . $spaceType->id) . '" class="btn btn-xs svbtn delete-warning"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function query()
    {
        $query = SpaceType::where('lang', 'en')->where('deleted_status', 'No')->select();
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'name', 'name' => 'space_type.name', 'title' => 'Name'])
            ->addColumn(['data' => 'description', 'name' => 'space_type.description', 'title' => 'Description'])
            ->addColumn(['data' => 'status', 'name' => 'space_type.status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters([
                'dom' => 'lBfrtip',
                'buttons' => [],
                'order' => [0, 'desc'],
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
        return 'spacetypedatatables_' . time();
    }
}
