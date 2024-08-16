<?php

/**
 * Amenities Controller
 *
 * Amenities Controller manages Amenities by admin.
 *
 * @category   Amenities
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
use App\DataTables\AmenitiesDataTable;
use App\Models\Amenities;
use App\Models\AmenityType;
use Validator;
use App\Http\Helpers\Common;
use App\Models\Language;
use DB;

class AmenitiesController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(AmenitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.amenities.view');
    }

    public function add(Request $request)
    {
        $info = $request->isMethod('post');
        if (! $info) {
            $amenity_type=AmenityType::where(['lang'=>'en'])->get();
            $am = [];
            foreach ($amenity_type as $key => $value) {
                $am[$value->id]=$value->name;
            }
            $data['am'] = $am;
			$data['amenity_type'] = $amenity_type;
			$data['languages'] 	  = Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 
            return view('admin.amenities.add', $data);
        } elseif ($info) {
            $rules = array(
                    /* 'title'          => 'required|max:50',
                    'description'    => 'required|max:100',
                    'symbol'         => 'required|max:50',
                    'type_id'        => 'required',
                    'status'         => 'required' */

                    );

            $fieldNames = array(
                        'title'             => 'Title',
                        'description'       => 'Description',
                        'symbol'            => 'Symbol'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                /* $amenitie = new Amenities;
                $amenitie->title            = $request->title;
                $amenitie->description    = $request->description;
                $amenitie->symbol           = $request->symbol;
                $amenitie->type_id        = $request->type_id;
                $amenitie->status         = $request->status;
                $amenitie->save(); */
				
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'amenities'");
				if (!empty($table)) 
				{ 
					$temp_id = $table[0]->AUTO_INCREMENT;
				}
				else
				{
					$temp_id = "";
				}
				for ($i=0; $i < count($lang_id); $i++) {
						$newTemplate 			= new Amenities;
						$newTemplate->temp_id   = $temp_id;
						if(isset($data[$i]['title']))
						{
							$newTemplate->title  	= $data[$i]['title'];
						}
						if(isset($data[$i]['symbol']))
						{
							$newTemplate->symbol  	= $data[$i]['symbol'];
						}
						if(isset($data[$i]['type_id']))
						{
							$newTemplate->type_id  	= $data[$i]['type_id'];
						}
						$newTemplate->lang      = $lang[$i];
						if(isset($data[$i]['description']))
						{
							$newTemplate->description   = $data[$i]['description'];
						}
						$newTemplate->lang_id   = $lang_id[$i];
						                        
						if(isset($data[$i]['status'])) 
						{
							$newTemplate->status    = $data[$i]['status'];
						}
						$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/amenities');
            }
        }
    }
    public function update(Request $request)
    {
        $info = $request->isMethod('post');
        if (! $info) {
               $amenity_type=AmenityType::where(['lang'=>'en'])->get();
               $am = [];
            foreach ($amenity_type as $key => $value) {
                 $am[$value->id]=$value->name;
            }
            $data['am']   = $am;
            $data['result'] = Amenities::find($request->id);
			$data['languages'] 		= Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 
			$data['amenity_type']	= $amenity_type;
			
            return view('admin.amenities.edit', $data);
        } elseif ($info) {
            $rules = array(
                    /* 'title'          => 'required|max:50',
                    'description'    => 'required|max:100',
                    'symbol'         => 'required|max:50',
                    'type_id'        => 'required',
                    'status'         => 'required' */

                    );

            $fieldNames = array(
                        'title'             => 'Title',
                        'description'       => 'Description',
                        'symbol'            => 'Symbol'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $amenitie = Amenities::find($request->id);
                /* $amenitie->title          = $request->title;
                $amenitie->description    = $request->description;
                $amenitie->symbol         = $request->symbol;
                $amenitie->type_id        = $request->type_id;
                $amenitie->status         = $request->status;
                $amenitie->save(); */
				
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = Amenities::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= Amenities::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['title']))
						{
							$templateToUpdate->title  	= $data[$i]['title'];
						}
						if(isset($data[$i]['description']))
						{
							$templateToUpdate->description  	= $data[$i]['description'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						if(isset($data[$i]['symbol']))
						{
							$templateToUpdate->symbol  	= $data[$i]['symbol'];
						}
						if(isset($data[$i]['type_id']))
						{
							$templateToUpdate->type_id  	= $data[$i]['type_id'];
						}
						$templateToUpdate->save();
					} else {
						$newTemplate 			= new Amenities;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['title']))
						{
							$newTemplate->title  	= $data[$i]['title'];
						}
						if(isset($data[$i]['description']))
						{				
							$newTemplate->description     	= $data[$i]['description'];
						}
						$newTemplate->lang      = $lang[$i];
						if(isset($data[$i]['symbol']))
						{
							$newTemplate->symbol  	= $data[$i]['symbol'];
						}
						if(isset($data[$i]['type_id']))
						{
							$newTemplate->type_id  	= $data[$i]['type_id'];
						}
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}

                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/amenities');
            }
        }
    }

    public function delete(Request $request)
    {
        //Amenities::find($request->id)->delete();
		//Amenities::where('temp_id',$request->id)->delete();
		
        Amenities::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status'=>'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/amenities');
    }
}
