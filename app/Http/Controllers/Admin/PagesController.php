<?php

/**
 * Pages Controller
 *
 * Pages Controller manages Pages by admin.
 *
 * @category   Pages
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
use App\DataTables\PagesDataTable;
use App\Models\Page;
use App\Models\Language;

use Validator;use DB;
use App\Http\Helpers\Common;

class PagesController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }
    
    public function index(PagesDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.view');
    }
    
    public function add(Request $request)
    {
        if (! $request->isMethod('post')) {
		$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

             return view('admin.pages.add', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /* 'name'           => 'required|max:100',
                    'url'            => 'required|unique:pages|max:100',
                    'content'        => 'required',
                    'position'       => 'required|max:40' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'url'               => 'Url',
                        'content'           => 'Content',
                        'position'          => 'Position'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {


                /* $page = new Page;
                $page->name             = $request->name;
                $page->url              = $request->url;
                $page->content          = $request->content;
                $page->position         = $request->position;
                $page->status           = $request->status;

                $page->save(); */
				
				unset($request['_token']);
				//unset($request['_wysihtml5_mode']);
				
				//print_r($request->all());exit;
				
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'pages'");
				if (!empty($table)) 
				{ 
					$temp_id = $table[0]->AUTO_INCREMENT;
				}
				else
				{
					$temp_id = "";
				}
				for ($i=0; $i < count($lang_id); $i++) {
						$newTemplate 			= new Page;
						$newTemplate->temp_id   = $temp_id;
                        
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['url']))
						{
							$newTemplate->url     = $data[$i]['url'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->content   = $data[$i]['content'];
						$newTemplate->lang_id   = $lang_id[$i];
						if(isset($data[$i]['position']))
						{
							$newTemplate->position  = $data[$i]['position'];
						}
                        
						if(isset($data[$i]['status']))
						{
							$newTemplate->status    = $data[$i]['status'];
						}
						$newTemplate->save();
    
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/pages');
            }
        }
    }

    public function update(Request $request)
    {
        if (!$request->isMethod('post')) {
				$id=$request->id;
               
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            $data['result']    = Page::find($request->id); 
										
			$data['list_menu']  = 'menu-'.$request->id;
			$data['tempId']     = $request->id;
			//print_r($data);exit;
            return view('admin.pages.edit', $data);
        } else if ($request->isMethod('post')) {
            $rules = array(
                    /* 'name'           => 'required|max:100',
                    'url'            => 'required|max:100',
                    'content'        => 'required',
                    'position'       => 'required|max:40' */
                    );

            $fieldNames = array(
                        'name'          => 'Name',
                        'url'           => 'Url',
                        'content'       => 'Content',
                        'position'      => 'Position'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
               /*  $page = Page::find($request->id);

                $page->name            = $request->name;
                $page->url             = $request->url;
                $page->content         = $request->content;
                $page->position        = $request->position;
                $page->status          = $request->status;
                $page->save(); */
				
				unset($request['_token']);
				//unset($request['_wysihtml5_mode']);
			
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = Page::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					
					if ($check) 
					{
						$templateToUpdate 			= Page::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['url']))
						{
							$templateToUpdate->url    	= $data[$i]['url'];
						}
						$templateToUpdate->content  = $data[$i]['content'];
						if(isset($data[$i]['position']))
						{							
							$templateToUpdate->position = $data[$i]['position'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						$templateToUpdate->save();
					} else {
						$newTemplate 			= new Page;
						$newTemplate->temp_id   = $id;
                        if(isset($data[$i]['name']))
						{
						    $newTemplate->name  = $data[$i]['name'];
                        }
                        if(isset($data[$i]['url']))
						{
						    $newTemplate->url   = $data[$i]['url'];
                        }
						$newTemplate->lang      = $lang[$i];
                        if(isset($data[$i]['content']))
						{
						    $newTemplate->content   = $data[$i]['content'];
                        }
						$newTemplate->position  = $data[$i]['position'];
						$newTemplate->status    = $data[$i]['status'];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save();
					}
				}

                $this->helper->one_time_message('success', 'Updated Successfully');

                return redirect('admin/pages');
            }
        }
    }

    public function delete(Request $request)
    {
        //Page::find($request->id)->delete();
		Page::where('temp_id',$request->id)->delete();

        $this->helper->one_time_message('success', 'Deleted Successfully');

        return redirect('admin/pages');
    }
    public function uploadImage(Request $request)
    { 
        $CKEditor = $request->input('CKEditor');
        $funcNum  = $request->input('CKEditorFuncNum');
        $message  = $url = '';
        if ($request->file('upload')) {
            $file = $request->upload;

            if ($file->isValid()) {

                $filename =rand(1000,9999).$file->getClientOriginalName();

                $file->move(public_path().'/images/', $filename);
                $url = url('public/images/' . $filename);

            } else {
                $message = 'An error occurred while uploading the file.';
            }
        } else {
            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }
}
