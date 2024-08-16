<?php

/**
 * AmenityTypeDataTable Data Table
 *
 * AmenityTypeDataTable Data Table handles AmenityTypeDataTable datas.
 *
 * @category   AmenityTypeDataTable
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\DataTables;

use App\Models\AmenityType;
use Yajra\DataTables\Services\DataTable;

class AmenityTypeDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($amenityType) {

                $edit   = '<a href="' . url('admin/settings/edit-amenities-type/' . $amenityType->id) . '" class="btn btn-xs svbtn"><i class="fa fa-pencil"></i></a>';
                $delete = '<a href="' . url('admin/settings/delete-amenities-type/' . $amenityType->id) . '" class="btn btn-xs svbtn delete-warning"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function query()
    {
        $query = AmenityType::where('lang', 'en')->where('deleted_status', 'No' )->select();

        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'name', 'name' =>'amenity_type.name', 'title' => 'Name'])
            ->addColumn(['data' => 'description', 'name' =>'amenity_type.description', 'title' => 'Description'])
            ->addColumn(['data' => 'action', 'name' =>'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
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
        return 'amenitytypedatatables_' . time();
    }
}
