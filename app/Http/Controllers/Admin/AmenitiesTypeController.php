<?php

/**
 * AmenitiesType Controller
 *
 * AmenitiesType Controller manages Amenity types added by admin.
 *
 * @category   AmenitiesType
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AmenityTypeDataTable;
use App\Models\AmenityType;
use Validator;
use App\Http\Helpers\Common;
use App\Models\Language;
use DB;

class AmenitiesTypeController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(AmenityTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.amenityTypes.view');
    }

    public function add(Request $request)
    {
        
        $info = $request->isMethod('post');
        if (! $info) {
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

             return view('admin.amenityTypes.add', $data);
        } elseif ($info) {
            $rules = array(
                    /* 'name'           => 'required|max:50',
                    'description'    => 'required|max:100' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'description'       => 'Description'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                /* $amenityType = new AmenityType;
                $amenityType->name           = $request->name;
                $amenityType->description    = $request->description;
                $amenityType->save(); */
				
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'amenity_type'");
				if (!empty($table)) 
				{ 
					$temp_id = $table[0]->AUTO_INCREMENT;
				}
				else
				{
					$temp_id = "";
				}
				for ($i=0; $i < count($lang_id); $i++) {
						$newTemplate 			= new AmenityType;
						$newTemplate->temp_id   = $temp_id;
                        
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->description   = $data[$i]['description'];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save();
				}
				

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/settings/amenities-type');
            }
        }
    }
    public function update(Request $request)
    {
        $info = $request->isMethod('post');
        if (! $info) {
            $data['result'] = AmenityType::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.amenityTypes.edit', $data);
        } elseif ($info) {
            $rules = array(
                    /* 'name'         => 'required|max:50',
                    'description'  => 'required|max:100' */
                    );

            $fieldNames = array(
                        'name'            => 'Name',
                        'description'     => 'Description'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $amenityType = AmenityType::find($request->id);
                /* $amenityType->name           = $request->name;
                 $amenityType->description    = $request->description;
                 $amenityType->save(); */
				 
				 unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = AmenityType::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					if ($check) 
					{
						$templateToUpdate 			= AmenityType::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['description']))
						{
							$templateToUpdate->description  	= $data[$i]['description'];
						}
						$templateToUpdate->save();
					} else {
						 $newTemplate 			= new AmenityType;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name = $data[$i]['name'];
						}
						if(isset($data[$i]['description']))
						{				
							$newTemplate->description = $data[$i]['description'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}
				 
                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/settings/amenities-type');
            }
        }
    }

    public function delete(Request $request)
    {
        //AmenityType::find($request->id)->delete();
		//AmenityType::where('temp_id',$request->id)->delete();
		
        AmenityType::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/settings/amenities-type');
    }
}
