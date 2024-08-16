<?php

/**
 * PropertyType Controller
 *
 * PropertyType Controller manages Property Types by admin.
 *
 * @category   PropertyType
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
use App\DataTables\PropertyTypeDataTable;
use App\Models\PropertyType;
use Validator;
use App\Models\Language;
use DB;
use App\Http\Helpers\Common;

class PropertyTypeController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }
    
    public function index(PropertyTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.propertyTypes.view');
    }
    
    public function add(Request $request)
    {
        if (! $request->isMethod('post')) 
		{
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.propertyTypes.add', $data);
        } 
		elseif ($request->isMethod('post'))
		{
            $rules = array(
                  /*   'name'           => 'required|max:100',
                    'description'    => 'required|max:255',
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
               /*  $propertyType                = new PropertyType;
                $propertyType->name          = $request->name;
                $propertyType->description   = $request->description;
                $propertyType->status        = $request->status;
                $propertyType->save(); */
				
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'property_type'");
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
						$newTemplate 			= new PropertyType;
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
						if(isset($data[$i]['icon']))
						{
							$path = 'public/images/property_type/';
						    //upload new file
 						    $file       =$data[$i]['icon'];
							$filename   = $file->getClientOriginalName();
							$file->move($path, $filename);
							$newTemplate->icon    = $filename;
						}
						$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/settings/property-type');
            }
        }
    }

    public function update(Request $request)
    {
        if (! $request->isMethod('post')) {
            $data['result']	   = PropertyType::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.propertyTypes.edit', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /*  'name'           => 'required|max:110',
                    'description'    => 'required|max:255',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'          => 'Name',
                        'description'   => 'Description',
                        'status'        => 'Status',
                        'icon'           => 'image|mimes:jpg,jpeg,png,JPG',
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $propertyType  = PropertyType::find($request->id);

                /* $propertyType->name          = $request->name;
                $propertyType->description   = $request->description;
                $propertyType->status        = $request->status;
                $propertyType->save(); */
				
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = PropertyType::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= PropertyType::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
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
						
						if(isset($data[$i]['icon']))
						{
							$path = 'public/images/property_type/';
							
							 if($templateToUpdate->icon != ''  && $templateToUpdate->icon != null){
								$file_old = $path.'/'.$templateToUpdate->icon;
								 unlink($file_old);
							 }
													    //upload new file
 						    $file       =$data[$i]['icon'];
							$filename   = $file->getClientOriginalName();
							$file->move($path, $filename);
							$templateToUpdate->icon    = $filename;
						}
						
						$templateToUpdate->save();
					} else {
						 $newTemplate 			= new PropertyType;
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

                return redirect('admin/settings/property-type');
            }
        }
    }

    public function delete(Request $request)
    {
        //PropertyType::find($request->id)->delete();
		
		//PropertyType::where('temp_id',$request->id)->delete();
        PropertyType::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status'=>'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/settings/property-type');
    }
}
