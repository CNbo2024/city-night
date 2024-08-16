<?php

/**
 * SpaceType Controller
 *
 * SpaceType Controller manages SpaceType by admin.
 *
 * @category   SpaceType
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @email      support@migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\SpaceTypeDataTable;
use App\Models\SpaceType;
use Validator;
use App\Models\Language;
use DB;

use App\Http\Helpers\Common;

class SpaceTypeController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }
    
    public function index(SpaceTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.spaceTypes.view');
    }
    
    public function add(Request $request)
    {
        if (! $request->isMethod('post')) {
			 $data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

             return view('admin.spaceTypes.add', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                    /* 'name'           => 'required|max:25',
                    'description'    => 'required',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'description'       => 'Description',
                        'status'            => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                /* $spaceType                = new SpaceType;
                $spaceType->name          = $request->name;
                $spaceType->description   = $request->description;
                $spaceType->status        = $request->status;
                $spaceType->save(); */
				
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'space_type'");
				if (!empty($table)) 
				{ 
					$temp_id = $table[0]->AUTO_INCREMENT;
				}
				else
				{
					$temp_id = "";
				}
				for ($i=0; $i < count($lang_id); $i++) 
				{
						$newTemplate 			= new SpaceType;
						$newTemplate->temp_id   = $temp_id;
                        
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						
						$newTemplate->lang      = $lang[$i];
						$newTemplate->description   = $data[$i]['description'];
						$newTemplate->lang_id   = $lang_id[$i];
						if(isset($data[$i]['status']))
						{
							$newTemplate->status    = $data[$i]['status'];
						}
						$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/settings/space-type');
            }
        }
    }

    public function update(Request $request)
    {
        if (! $request->isMethod('post')) {
             $data['result'] = SpaceType::find($request->id);
			 $data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

             return view('admin.spaceTypes.edit', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /*  'name'           => 'required|max:25',
                    'description'    => 'required',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'description'       => 'Description',
                        'status'            => 'Status'
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $spaceType  = SpaceType::find($request->id);
               /*  $spaceType->name          = $request->name;
                $spaceType->description   = $request->description;
                $spaceType->status        = $request->status;
                $spaceType->save(); */
				
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = SpaceType::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= SpaceType::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['description']))
						{
							$templateToUpdate->description  	= $data[$i]['description'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						$templateToUpdate->save();
					} else {
						 $newTemplate 			= new SpaceType;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['description']))
						{				
							$newTemplate->description     	= $data[$i]['description'];
						}
						$newTemplate->lang      = $lang[$i];
						
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}

                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/settings/space-type');
            }
        }
    }

    public function delete(Request $request)
    {
        //SpaceType::find($request->id)->delete();
		
		//SpaceType::where('temp_id',$request->id)->delete();
        SpaceType::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status'=>'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/settings/space-type');
    }
}
