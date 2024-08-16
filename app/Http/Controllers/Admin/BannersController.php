<?php

/**
 * Banners Controller
 *
 * Banners Controller manages banners in home page.
 *
 * @category   Banners
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
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BannersDataTable;
use App\Models\Banners;
use App\Http\Helpers\Common;
use Validator;
use App\Models\Language;
use DB;


class BannersController extends Controller
{
    protected $helper;  

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(BannersDataTable $dataTable)
    {
        return $dataTable->render('admin.banners.view');
    }

    public function add(Request $request)
    {
        if (! $request->isMethod('post')) {
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.banners.add',$data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                'heading'    => 'required|max:100',
                'image'      => 'required|dimensions:min_width=1920,min_height=860' 
            );

            
            $fieldNames = array(
                'heading'    => 'Heading',
                'image'      => 'Image'
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);
			
            if ($validator->fails()) {
				
                return back()->withErrors($validator)->withInput();
            } else {
				
                $image     =   $request->file('image');
                $extension =   $image->getClientOriginalExtension();
				
                $filename  =   'banner_'.time() . '.' . $extension;

                $success   = $image->move('public/front/images/banners', $filename);
                
                if (!isset($success)) {
                    return back()->withError('Could not upload Image');
                }
				
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'banners'");
				if (!empty($table)) 
				{ 
					$temp_id = $table[0]->AUTO_INCREMENT;
				}
				else
				{
					$temp_id = "";
				}
									
                $banners = new Banners;
                $banners->heading  = $request->heading;
                $banners->image    = $filename;
                $banners->status   = $request->status;
				$banners->lang     = "en";
				$banners->lang_id   = "1";
				$banners->temp_id   = $temp_id;
                if (isset($request->subheading)) {
                    $banners->subheading = $request->subheading;
                }
                $banners->save(); 
				
				unset($request['_token']);
				unset($request['heading']);
				unset($request['subheading']);
				unset($request['status']);
				
				$ss = $request->except('image');
				//print_r($ss);exit;
				
				$id=$request->id;
				foreach ($ss as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				for ($i=0; $i < count($lang_id); $i++) 
				{
					if($lang_id[$i]!="1")
					{
						$newTemplate 			= new Banners;
						$newTemplate->temp_id   = $temp_id;
                        
						if(isset($data[$i]['heading1']))
						{
							$newTemplate->heading  	= $data[$i]['heading1'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->subheading   = $data[$i]['subheading1'];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->image      = $filename;                        
						if(isset($data[$i]['status']))
						{
							$newTemplate->status    = $data[$i]['status'];
						}
						$newTemplate->save();
					}
				} 

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/settings/banners');
            }
        } else {
            return redirect('admin/settings/banners');
        }
    }
    public function update(Request $request)
    {
        if (! $request->isMethod('post')) {
            $data['result'] = Banners::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.banners.edit', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                    'heading'    => 'required|max:100',
                    'image'      => 'dimensions:min_width=1920,min_height=860'

                    );

            $fieldNames = array(
                        'heading'    => 'Heading',
                        'image'      => 'dimensions:min_width=1920,min_height=860'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $banners = Banners::find($request->id);

                $banners->heading  = $request->heading;
                $banners->status   = $request->status;
                if (isset($request->subheading)) {
                    $banners->subheading = $request->subheading;
                }
                $image     =   $request->file('image');

                if ($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'banner_'.time() . '.' . $extension;
                    $success = $image->move('public/front/images/banners', $filename);
                    if (! isset($success)) {
                         return back()->withError('Could not upload Image');
                    }
                    $banners->image = $filename;
                }
                else
                {
                    $filename = "";
                }
                $banners->save();
				
				
				unset($request['_token']);
				unset($request['_token']);
				unset($request['heading']);
				unset($request['subheading']);
				unset($request['status']);
				
				$ss = $request->except('image');

				$id=$request->id;
				foreach ($ss as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = Banners::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= Banners::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['heading1']))
						{
							$templateToUpdate->heading  	= $data[$i]['heading1'];
						}
						if(isset($data[$i]['subheading1']))
						{
							$templateToUpdate->subheading  	= $data[$i]['subheading1'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						//$templateToUpdate->image      = $filename;    
						$templateToUpdate->save();
					} else {
						 $newTemplate 			= new Banners;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['heading1']))
						{
							$newTemplate->heading  	= $data[$i]['heading1'];
						}
						if(isset($data[$i]['subheading1']))
						{				
							$newTemplate->subheading     	= $data[$i]['subheading1'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						//$newTemplate->image      = $filename;    
						$newTemplate->save(); 
					}
				}

                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/settings/banners');
            }
        } else {
            return redirect('admin/settings/banners');
        }
    }
    public function delete(Request $request)
    {
        if (env('APP_MODE', '') != 'test') {
            $banners   = Banners::find($request->id);
            $file_path = public_path().'/front/images/banners/'.$banners->image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            //Banners::find($request->id)->delete();
			Banners::where('temp_id',$request->id)->delete();

            $this->helper->one_time_message('success', 'Deleted Successfully');
        }
        
        return redirect('admin/settings/banners');
    }
}
