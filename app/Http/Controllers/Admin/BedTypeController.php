<?php

/**
 * BedType Controller
 *
 * BedType Controller manages Bed Types by admin.
 *
 * @category   BedType
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
use App\DataTables\BedTypeDataTable;
use App\Models\BedType;
use Validator;
use App\Http\Helpers\Common;
use DB;
use App\Models\Language;

class BedTypeController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(BedTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.bedTypes.view');
    }

    public function add(Request $request)
    {
        $info = $request->isMethod('post');

        if (! $info) {
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.bedTypes.add', $data);
        } elseif ($info) {
            $rules = array(
                   // 'name'    => 'required|max:50'
                    );

            $fieldNames = array(
                        'name'  => 'Name'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                /* $bedType               = new BedType;
                $bedType->name         = $request->name;
                $bedType->save(); */
				
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'bed_type'");
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
						$newTemplate 			= new BedType;
						$newTemplate->temp_id   = $temp_id;
                        
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save();
				}


                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/settings/bed-type');
            }
        }
    }

    public function update(Request $request)
    {
        $info = $request->isMethod('post');
        if (! $info) {
            $data['result'] = BedType::find($request->id);
			 $data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.bedTypes.edit', $data);
        } elseif ($info) {
            $rules = array(
                    //'name'  => 'required|max:50'
                    );

            $fieldNames = array(
                        'name'    => 'Name'
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                if (env('APP_MODE', '') != 'test') {
                    /* $bedType = BedType::find($request->id);
                    $bedType->name   = $request->name;
                    $bedType->save(); */
					
				unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = BedType::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= BedType::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						$templateToUpdate->save();
					} else {
						$newTemplate 			= new BedType;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}
				
                }

                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/settings/bed-type');
            }
        }
    }

    public function delete(Request $request)
    {
        if (env('APP_MODE', '') != 'test') {
            //BedType::find($request->id)->delete();
			//BedType::where('temp_id',$request->id)->delete();
	        BedType::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes' ]);

        }

        $this->helper->one_time_message('success', 'Deleted Successfully');

        return redirect('admin/settings/bed-type');
    }
}
