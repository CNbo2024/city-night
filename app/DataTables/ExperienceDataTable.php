<?php

namespace App\DataTables;

use App\Models\Properties;
use Yajra\DataTables\Services\DataTable;
use Request;
use App\Http\Helpers\Common;

class ExperienceDataTable extends DataTable
{
    public function ajax()
    {
        $properties = $this->query();

        return datatables()
            ->of($properties)
            ->addColumn('action', function ($properties) {
                $edit = $delete = '';
                if (Common::has_permission(\Auth::guard('admin')->user()->id, 'edit_properties')) {
                    $edit = '<a href="' . url('admin/experience/' . $properties->properties_id) . '/basics" class="btn btn-xs svbtn"><i class="fa fa-pencil"></i></a>';
                }
                if (Common::has_permission(\Auth::guard('admin')->user()->id, 'delete_property')) {
                    
                    if(config('global.demosite')=="yes")
                    {
                        $delete = '<a href="#" class="btn btn-xs svbtn"><i class="fa fa-trash"></i> </a><p class="disabletxt">('.config('global.demotxt').')</p>';
                    }
                    else
                    {
                        $delete = '<a href="' . url('admin/delete-experience/' . $properties->properties_id) . '" class="btn btn-xs svbtn delete-warning"><i class="fa fa-trash"></i></a>';
                    }
                } 
                return $edit . $delete;
            })
            ->addColumn('host_name', function ($properties) {
                return '<a href="' . url('admin/edit-customer/' . $properties->host_id) . '">' . ucfirst($properties->first_name) . '</a>';
            })
            ->addColumn('property_name', function ($properties) {
                return '<a href="' . url('admin/experience/' . $properties->properties_id . '/basics') . '">' . ucfirst($properties->property_name) . '</a>';
            })
            ->addColumn('property_created_at', function ($properties) {
                return dateFormat($properties->property_created_at);
            })
                     
            ->addColumn('admin_approval_status', function ($properties) {
				
				if($properties->admin_approval==0) { $txt = '<span class="text-danger">Disapproved</span>'; } else { $txt = '<span class="text-success">Approved</span>'; }
                $approve = $txt;
                return $approve;
            })
            
            ->addColumn('admin_approval', function ($properties) {
				if(config('global.demosite')=="yes"){
					if($properties->admin_approval==0)
					{
						$status_text = '<a href="javascript:void(0);" style="color:#fff" class="btn btn-xs btn-success btndisable">Approve</a><p class="disabletxt">('.config('global.demotxt').')</p>&nbsp;';
                    }
					else
					{
						$status_text = '<a href="javascript:void(0);" style="color:#fff" class="btn btn-xs btn-primary btndisable">Disapprove</a><p class="disabletxt">('.config('global.demotxt').')</p>';
					}
				}
				else
			    {
				   if($properties->admin_approval==0)
				   {					   
						$status_text = '<a href="'.url('admin/properties/'.$properties->properties_id).'/1" class="btn btn-xs btn-success" style="color:#fff">Approve</a>&nbsp;';
				   }
				   else
				   {
						$status_text = '<a href="'.url('admin/properties/'.$properties->properties_id).'/0" class="btn btn-xs btn-primary" style="color:#fff">Disapprove</a>';
				   }
				}
                
                return $status_text;
            })
            
            ->rawColumns(['property_name', 'host_name','action','admin_approval','admin_approval_status'])
            ->make(true);
    }

    public function query()
    {
        $user_id    = Request::segment(4);
        $status     = isset(request()->status) ? request()->status : null;
        $from       = isset(request()->from) ? setDateForDb(request()->from) : null;
        $to         = isset(request()->to) ? setDateForDb(request()->to) : null;
        $space_type = isset(request()->space_type) ? request()->space_type : null;
        $query      = Properties::join('users', function ($join) {
                                $join->on('users.id', '=', 'properties.host_id');
        })
                        ->join('space_type', function ($join) {
                                $join->on('space_type.id', '=', 'properties.space_type');
                        })

                        ->select(['properties.id as properties_id', 'properties.name as property_name', 'properties.status as property_status', 'properties.recomended as property_recomended', 'properties.created_at as property_created_at', 'properties.updated_at as property_updated_at', 'properties.*', 'users.*', 'space_type.*']);

        if (isset($user_id)) {
            $query->where('properties.host_id', '=', $user_id);
        }

        if ($from) {
             $query->whereDate('properties.created_at', '>=', $from);
        }
        if ($to) {
             $query->whereDate('properties.created_at', '<=', $to);
        }
        if ($status) {
            $query->where('properties.status', '=', $status);
        }
        if ($space_type) {
            $query->where('properties.space_type', '=', $space_type);
        }

		$query->where('properties.type', '=', 'experience');
		$query->where('properties.deleted_status', '=', 'No');
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'properties_id', 'name' => 'properties.id', 'title' => 'Id'])
            ->addColumn(['data' => 'property_name', 'name' => 'properties.name', 'title' => 'Name'])
            ->addColumn(['data' => 'host_name', 'name' => 'users.first_name', 'title' => 'Host Name'])
            ->addColumn(['data' => 'property_status', 'name' => 'properties.status', 'title' => 'Status'])
			->addColumn(['data' => 'admin_approval_status', 'name' => 'admin_approval', 'title' => 'Admin Status'])
			->addColumn(['data' => 'admin_approval', 'name' => 'admin_approval', 'title' => 'Approval'])

            ->addColumn(['data' => 'property_created_at', 'name' => 'properties.created_at', 'title' => 'Date'])
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
        return 'propertydatatables_' . time();
    }
}
