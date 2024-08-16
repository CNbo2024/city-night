<?php

/**
 * Properties Controller
 *
 * Properties Controller manages Properties by admin.
 *
 * @category   Properties
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

use Session;
use PDF;
use Validator;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ExperienceDataTable; 
use App\DataTables\InclusionDataTable;
use App\DataTables\ExclusionDataTable;
use App\Exports\CustomersExport;
use App\Exports\PropertiesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Helpers\Common;
use App\Http\Controllers\Admin\CalendarController;
use App\DataTables\ExperienceCategoryDataTable;


use App\Models\{
    Properties,
    PropertyDetails,
    PropertyAddress,
    PropertyPhotos,
    PropertyPrice,
    PropertyType,
    PropertyDates,
    PropertyDescription,
    Currency,
    SpaceType,
    BedType,
    PropertySteps,
    Country,
    Amenities,
    AmenityType,
    User,
    Settings,
    Bookings,
	Language,
	PropertyMeta,
	ExperienceCategory,
	Inclusion,
	Exclusion,
	FamilyPackage,
	PropertyTime
};

class ExperienceController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(ExperienceDataTable $dataTable)
    {
        $data['from'] = isset(request()->from) ? request()->from : null;
        $data['to']   = isset(request()->to) ? request()->to : null;

        if (isset(request()->reset_btn)) {
            $data['from']        = null;
            $data['to']          = null;
            $data['allstatus']   = '';
            return $dataTable->render('admin.experience.view', $data);
        }
        isset(request()->status) ? $data['allstatus'] = $allstatus = request()->status : $data['allstatus'] = $allstatus = '';
        return $dataTable->render('admin.experience.view', $data);
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = array(
              'property_type_id'  => 'required',
              'space_type'        => 'required',
              'accommodates'      => 'required',
              'map_address'       => 'required',
              'host_id'           => 'required',
            );

            $fieldNames = array(
              'property_type_id'  => 'Home Type',
              'space_type'        => 'Room Type',
              'accommodates'      => 'Accommodates',
              'map_address'       => 'City',
              'host_id'           => 'Host'
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $property                  = new Properties;
                $property->host_id         = $request->host_id;
                $property->name            = SpaceType::find($request->space_type)->name.' in '.$request->city;
                $property->property_type   = $request->property_type_id;
                $property->space_type      = $request->space_type;
                $property->accommodates    = $request->accommodates;
                $property->save();

                $property_address                 = new PropertyAddress;
                $property_address->property_id    = $property->id;
                $property_address->address_line_1 = $request->route;
                $property_address->city           = $request->city;
                $property_address->state          = $request->state;
                $property_address->country        = $request->country;
                $property_address->postal_code    = $request->postal_code;
                $property_address->latitude       = $request->latitude;
                $property_address->longitude      = $request->longitude;
                $property_address->save();

                $property_price                 = new PropertyPrice;
                $property_price->property_id    = $property->id;
                $property_price->currency_code  = \Session::get('currency');
                $property_price->save();

                $property_steps                   = new PropertySteps;
                $property_steps->property_id      = $property->id;
                $property_steps->save();

                $property_description              = new PropertyDescription;
                $property_description->property_id = $property->id;
                $property_description->save();

                return redirect('admin/experience/'.$property->id.'/basics');
            }
        }

        $data['property_type'] = PropertyType::where('status', 'Active')->where('lang', 'en')->pluck('name', 'id');
        $data['space_type']    = SpaceType::where('status', 'Active')->where('lang', 'en')->pluck('name', 'id');
        $data['users']         = User::where('status', 'Active')->get();
        return view('admin.properties.add', $data);
    }

    public function listing(Request $request, CalendarController $calendar)
    {
		$current_lang = Session::get('language');

        if($current_lang=="")
        {
            $current_lang = "en";
        }
        else
        {
            $current_lang = $current_lang;
        }
        
        $step            = $request->step;
        $property_id     = $request->id;

        $data['step']    = $step;
        $data['result']  = Properties::findOrFail($property_id);
        $data['details'] = PropertyDetails::pluck('value', 'field');
        
        if ($step == 'basics') {
                  if ($request->isMethod('post')) {
				
                $property                     = Properties::find($property_id);
                $property->experience_type    = $request->experience_type;
                $property->name         	  = $request->name;
                $property->slug     		  = $this->helper->pretty_url($request->name);
                $property->accommodates       = $request->accommodates;
				$property->duration       	  = $request->duration;
				 $property->recomended         = $request->recomended;
                $property->save();

                $property_steps         = PropertySteps::where('property_id', $property_id)->first();
                $property_steps->basics = 1;
                $property_steps->save();
				
                return redirect('admin/experience/'.$property_id.'/description');
            }
			$data['title']  	= "Basics";
			$data['category']   = ExperienceCategory::where('status', 'Active')->where('lang', 'en')->get();

        } elseif ($step == 'description') {
            if ($request->isMethod('post')) {
                $rules = array(
                    'name'     => 'required|max:50',
                    'summary'  => 'required|max:1000',
                );
        
                $fieldNames = array(
                    'name'     => 'Name',
                    'summary'  => 'Summary',
                );

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($fieldNames);

                if ($validator->fails()) 
                {
                    return back()->withErrors($validator)->withInput();
                }
                else 
                {
                    $property           = Properties::find($property_id);
                    $property->name     = $request->name;                
                    $property->slug     = $this->helper->pretty_url($request->name);
                    $property->save();

                    $property_description              = PropertyDescription::where('property_id', $property_id)->first();
                    $property_description->summary     = $request->summary;
                    $property_description->save();

                    $property_steps = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->description = 1;
                    $property_steps->save();
					
					unset($request['_token']);
					unset($request['name']);
					unset($request['summary']);
					$id=$request->id;
					
					foreach ($request->all() as $key => $value) {
						$lang[] 	= $key; 
						$lang_id[]  = $value['id'];
						unset($value['id']);
						$data[]     = $value;
					}
					
					for ($i=0; $i < count($lang_id); $i++) 
					{
						$check = PropertyMeta::where('lang_id', '=', $lang_id[$i])
											->where('property_id', '=', $property_id)
											->first();
											
						if($lang_id[$i]!="1")
						{
							if ($check) 
							{
								$templateToUpdate 			= PropertyMeta::where([['property_id',$property_id],['lang_id', $lang_id[$i]]])->first();
								if(isset($data[$i]['name1']))
								{
									$templateToUpdate->name  	= $data[$i]['name1'];
								}
								if(isset($data[$i]['description1']))
								{
									$templateToUpdate->summary  = $data[$i]['description1'];
								}
								if(isset($data[$i]['lang']))
								{
									$templateToUpdate->lang   = $lang[$i];
								}
								if(isset($data[$i]['lang_id']))
								{
									$templateToUpdate->lang_id   = $lang_id[$i];
								}
								$templateToUpdate->save(); 
							}
							else
							{
								$newTemplate 			= new PropertyMeta;	
								if(isset($data[$i]['name1']))
								{
									$newTemplate->name  	= $data[$i]['name1'];
								}
								$newTemplate->property_id   = $property_id;
								$newTemplate->lang      	= $lang[$i];
								if(isset($data[$i]['description1']))
								{
									$newTemplate->summary   	= $data[$i]['description1'];
								}
								$newTemplate->lang_id  		= $lang_id[$i];
								$newTemplate->save();
							}
						}
				  }
					
                    return redirect('admin/experience/'.$property_id.'/details');
                }
            }
            $data['description']       = PropertyDescription::where('property_id', $property_id)->first();
			$data['languages_new'] 	   = Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 

        } elseif ($step == 'details') {
            

            if ($request->isMethod('post')) {
                $property_description                       = PropertyDescription::where('property_id', $property_id)->first();
                $property_description->about_place          = $request->about_place;
                $property_description->place_is_great_for   = $request->place_is_great_for;
                $property_description->guest_can_access     = $request->guest_can_access;
                $property_description->interaction_guests   = $request->interaction_guests;
                $property_description->other                = $request->other;
                $property_description->about_neighborhood   = $request->about_neighborhood;
                $property_description->get_around           = $request->get_around;
                $property_description->save();
				
				unset($request['_token']);
				unset($request['about_place']);
				unset($request['place_is_great_for']);
				unset($request['guest_can_access']);
				unset($request['interaction_guests']);
				unset($request['other']);
				unset($request['about_neighborhood']);
				unset($request['get_around']);
				$id=$request->id;
				
				foreach ($request->all() as $key => $value) {
						$lang[] 	= $key; 
						$lang_id[]  = $value['id'];
						unset($value['id']);
						$data[]     = $value;
				}
				
				for ($i=0; $i < count($lang_id); $i++) {
					$check = PropertyMeta::where('lang_id', '=', $lang_id[$i])
											->where('property_id', '=', $property_id)
											->first();
					if($lang_id[$i]!="1")
						{
							
					
					if ($check) 
					{
						$templateToUpdate 			= PropertyMeta::where([['property_id',$property_id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['about_place1']))
						{
							$templateToUpdate->about_place  	= $data[$i]['about_place1'];
						}
						if(isset($data[$i]['place_is_great_for1']))
						{
							$templateToUpdate->place_is_great_for  	= $data[$i]['place_is_great_for1'];
						}
						if(isset($data[$i]['guest_can_access1']))
						{
							$templateToUpdate->guest_can_access   = $data[$i]['guest_can_access1'];
						}
						if(isset($data[$i]['interaction_guests1']))
						{
							$templateToUpdate->interaction_guests   = $data[$i]['interaction_guests1'];
						}
						if(isset($data[$i]['other1']))
						{
							$templateToUpdate->other   = $data[$i]['other1'];
						}
						if(isset($data[$i]['about_neighborhood1']))
						{
							$templateToUpdate->about_neighborhood   = $data[$i]['about_neighborhood1'];
						}
						if(isset($data[$i]['get_around1']))
						{ 
							$templateToUpdate->get_around   = $data[$i]['get_around1'];
						}
						$templateToUpdate->save(); 
					} else {
						$newTemplate 			= new PropertyMeta;
						$newTemplate->property_id   = $property_id;
						if(isset($data[$i]['about_place1']))
						{
							$newTemplate->about_place  	= $data[$i]['about_place1'];
						}
						if(isset($data[$i]['place_is_great_for1']))
						{
							$newTemplate->place_is_great_for  	= $data[$i]['place_is_great_for1'];
						}
						if(isset($data[$i]['guest_can_access1']))
						{
							$newTemplate->guest_can_access   = $data[$i]['guest_can_access1'];
						}
						if(isset($data[$i]['interaction_guests1']))
						{
							$newTemplate->interaction_guests   = $data[$i]['interaction_guests1'];
						}
						if(isset($data[$i]['other1']))
						{
							$newTemplate->other   = $data[$i]['other1'];
						}
						if(isset($data[$i]['about_neighborhood1']))
						{
							$newTemplate->about_neighborhood   = $data[$i]['about_neighborhood1'];
						}
						if(isset($data[$i]['get_around1']))
						{
							$newTemplate->get_around   = $data[$i]['get_around1'];
						}
						$newTemplate->lang      = $lang[$i];
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
						}
				}
                return redirect('admin/experience/'.$property_id.'/location');
            }
			$data['languages_new']  = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

        } elseif ($step == 'location') {
            if ($request->isMethod('post')) {
                $rules = array(
                    'address_line_1'    => 'required|max:250',
                    //'address_line_2'    => 'max:250',
                    'country'           => 'required',
                    'city'              => 'required',
                    'state'             => 'required',
                    'latitude'          => 'required|not_in:0',
                );
            
                $fieldNames = array(
                    'address_line_1' => 'Address Line 1',
                    'country'        => 'Country',
                    'city'           => 'City',
                    'state'          => 'State',
                    'latitude'       => 'Map',
                );

                $messages = [
                    'not_in' => 'Please set :attribute pointer',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                $validator->setAttributeNames($fieldNames);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput(); 
                } else {
                    $property_address                 = PropertyAddress::where('property_id', $property_id)->first();
                    $property_address->address_line_1 = $request->address_line_1;
                    $property_address->address_line_2 = $request->address_line_2;
                    $property_address->latitude       = $request->latitude;
                    $property_address->longitude      = $request->longitude;
                    $property_address->city           = $request->city;
                    $property_address->state          = $request->state;
                    $property_address->country        = $request->country;
                    $property_address->postal_code    = $request->postal_code;
                    $property_address->save();

                    $property_steps = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->location = 1;
                    $property_steps->save();

                    return redirect('admin/experience/'.$property_id.'/amenities');
                }
            }
            $data['country']       = Country::pluck('name', 'short_name');
        } elseif ($step == 'amenities') {
           
            if ($request->isMethod('post') ) 
			{
                $rooms            = Properties::find($request->id);
                $rooms->exclusion = implode(',', $request->exclusion);
				$rooms->inclusion = implode(',', $request->inclusion);
				$rooms->amenities = '0';
                $rooms->save();
                return redirect('admin/experience/'.$property_id.'/photos');
            }
            $data['property_exclusion'] = explode(',', $data['result']->exclusion);
			 $data['property_inclusion'] = explode(',', $data['result']->inclusion);
            $data['inclusion']          = Inclusion::where('status', 'Active')->where('lang', $current_lang)->get();
            $data['exclusion']          = Exclusion::where('status', 'Active')->where('lang', $current_lang)->get();
			
			
        } elseif ($step == 'photos') {
            if ($_FILES) {
                
                /* if (isset($_FILES["file"]["name"])) {
                    
                if($request->crop == 'crop' && $request->photos) {
                    $baseText = explode(";base64,", $request->photos);
                    $name = explode(".", $request->img_name);
                    $convertedImage = base64_decode($baseText[1]);
                    $request->request->add(['type'=>end($name)]);
                    $request->request->add(['image'=>$convertedImage]);


                    $validate = Validator::make($request->all(), [
                        'type' => 'required|in:png,jpg,JPG,JPEG,jpeg,bmp',
                        'img_name' => 'required',
                        'photos' => 'required',
                    ]);
                } else {
                    $validate = Validator::make($request->all(), [
                        'file' => 'required|file|mimes:jpg,jpeg,bmp,png,gif,JPG',
                        'file' => 'dimensions:min_width=640,min_height=360'
                    ]);
                    
                }

                if($validate->fails()) {
                    return back()->withErrors($validate)->withInput();
                }

                $path = public_path('images/property/'.$property_id.'/');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $uploaded = "";
                if($request->crop == "crop" && $request->photos) {
                    $image = $name[0].uniqid().'.'.end($name);
                    $uploaded = file_put_contents($path . $image, $convertedImage);
                } else {
                    if (isset($_FILES["file"]["name"])) {
                        $tmp_name = $_FILES["file"]["tmp_name"];
                        $name = str_replace(' ', '_', $_FILES["file"]["name"]);
                        $ext = pathinfo($name, PATHINFO_EXTENSION);
                        $image = time() . '_' . $name;
                        $path = 'public/images/property/' . $property_id;
                        if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG') {
                            $uploaded = move_uploaded_file($tmp_name, $path . "/" . $image);
                        }
                    }
                }

                if ($uploaded) {
                    $photos = new PropertyPhotos;
                    $photos->property_id = $property_id;
                    $photos->photo = $image;
                    $photos->serial = 1;
                    $photos->cover_photo = 1;
                    $photos->type = 'photo';

                    $exist = PropertyPhotos::orderBy('serial', 'desc')
                        ->select('serial')
                        ->where('property_id', $property_id)
                        ->take(1)->first();

                    if (!empty($exist->serial)) {
                        $photos->serial = $exist->serial + 1;
                        $photos->cover_photo = 0;
                    }
                    $photos->save();
                    $property_steps = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->photos = 1;
                    $property_steps->save();
                }
                
            } */
            
            $rules = array(
                    'photos.*' => 'image|mimes:jpg,jpeg,bmp,png,gif,JPG',
                    'photos.*' => 'dimensions:min_width=640,min_height=360',
                );

                $fieldNames = array(
                    'photos.*'=> 'Photos'
                );

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($fieldNames);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                } else {
                    if (isset($_FILES["photos"]["name"])) {
                        foreach ($_FILES["photos"]["error"] as $key => $error) {
                            $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                            $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);
                            
                            $ext = pathinfo($name, PATHINFO_EXTENSION);

                            $name = time().'_'.$name;

                            $path = 'public/images/property/'.$property_id;
                                            
                            if (!file_exists($path)) {
                                mkdir($path, 0777, true);
                            }
                                                       
                            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG') {
                                if (move_uploaded_file($tmp_name, $path."/".$name)) {
                                    $photo_exist_first   = PropertyPhotos::where('property_id', $property_id)->where('type','photo')->count();
                                    if ($photo_exist_first!=0) {
                                        $photo_exist         = PropertyPhotos::orderBy('serial', 'desc')->where('property_id', $property_id)->where('type','photo')->take(1)->first();
                                    }
                                    $photos              = new PropertyPhotos;
                                    $photos->property_id = $property_id;
                                    $photos->photo       = $name;
                                    if ($photo_exist_first!=0) {
                                        $photos->serial = $photo_exist->serial+1;
                                    } else {
                                        $photos->serial = $photo_exist_first+1;
                                    }
                                    if (! $photo_exist_first) {
                                        $photos->cover_photo     = 1;
                                    }
                                    $photos->type       = 'photo';
                                    $photos->save();
                                    $property_steps         = PropertySteps::where('property_id', $property_id)->first();
                                    $property_steps->photos = 1;
                                    $property_steps->save();
                                }
                            }
                        }
                    }
                }
                
                if($request->video){
                        $url = $request->video;
                        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
                        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';
                    
                        if (preg_match($longUrlRegex, $url, $matches)) {
                            $youtube_id = $matches[count($matches) - 1];
                        }
                    
                        if (preg_match($shortUrlRegex, $url, $matches)) {
                            $youtube_id = $matches[count($matches) - 1];
                        }
                        $yt_url =  'https://www.youtube.com/embed/' . $youtube_id ;
                        
                        $photo_exist_first   = PropertyPhotos::where('property_id', $property_id)->where('type','video')->count();
                        if ($photo_exist_first!=0) {
                            $photo_exist         = PropertyPhotos::orderBy('serial', 'desc')->where('property_id', $property_id)->where('type','video')->take(1)->first();
                        }
                        $photos              = new PropertyPhotos;
                        $photos->property_id = $property_id;
                        $photos->photo       = $yt_url;
                         if ($photo_exist_first!=0) {
                            $photos->serial = $photo_exist->serial+1;
                        } else {
                            $photos->serial = $photo_exist_first+1;
                        }
                        $photos->type       = 'video';
                        $photos->save();
                    } 
                    if(empty($request->photo)){
                       return redirect('admin/experience/'.$property_id.'/photos')->with('danger', 'Please upload atleast one photo!'); 
                    }

                return redirect('admin/experience/'.$property_id.'/photos')->with('success', 'File Uploaded Successfully!');

            }
            
            $data['photos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','photo')
                                ->orderBy('serial', 'asc')
                                ->get();
            $data['videos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','video')
                                ->orderBy('serial', 'asc')
                                ->get();
        } else if ($step == 'pricing') {
            if ($request->isMethod('post')) {
                
                if($request->exp_booking_type=="3")
                {
                    $rules = array(
                    );
                }
                else
                {
                     $rules = array(
                        'price' => 'required|numeric|min:5',
                    );
                }
                $fieldNames = array(
                    'price'  => 'Price',
                );

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($fieldNames);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                } else {
                    $property_price                    = PropertyPrice::where('property_id', $property_id)->first();
                    $property_price->price             = $request->price;
                    $property_price->weekly_discount   = $request->weekly_discount;
                    $property_price->monthly_discount  = $request->monthly_discount;
                    $property_price->currency_code     = $request->currency_code;
                    $property_price->cleaning_fee      = $request->cleaning_fee;
                    $property_price->guest_fee         = $request->guest_fee;
                    $property_price->guest_after       = $request->guest_after;
                    $property_price->security_fee      = $request->security_fee;
                    $property_price->weekend_price     = $request->weekend_price;
                    $property_price->save();

                    $property_steps = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->pricing = 1;
                    $property_steps->save();
                    
                    $properties              	  = Properties::find($property_id);
					$properties->exp_booking_type = $request->exp_booking_type;
					$properties->save();
					
		
					if($request->exp_booking_type=="2")
					{
						if(isset($request->tid))
						{								
							foreach($request->tid as $key => $value)
							{ 
								if($request->tid[$key]!="")
								{
									$quarters 		        = PropertyTime::find($request->tid[$key]); 
									$quarters->start_time   = $request->start_time[$key];
									$quarters->property_id  = $property_id;
									$quarters->end_time     = $request->end_time[$key];
									$quarters->save(); 
								}
								else
								{
									DB::table('property_time')->insert(
									 array(
											'property_id'     => $property_id, 
											'start_time'      => $request->start_time[$key],
											'end_time'    	  => $request->end_time[$key],
											
									 )
									); 
								}
							}
						}
					}
					if($request->exp_booking_type=="3")
					{
					    if(isset($request->fid))
						{								
							foreach($request->fid as $key => $value)
							{ 
								if($request->fid[$key]!="")
								{
									$quarters 		        = FamilyPackage::find($request->fid[$key]); 
									$quarters->title        = $request->title[$key];
									$quarters->property_id  = $property_id;
									$quarters->price        = $request->fprice[$key];
									$quarters->adults       = $request->adults[$key];
									$quarters->children     = $request->children[$key];
									$quarters->infants      = $request->infants[$key];
									$quarters->itinerary    = $request->itinerary[$key];
									
									if(isset($request->file[$key] ))
									{        
										$path = 'public/images/experience/'.$property_id;

										  //code for remove old file
										  if($quarters->file != ''  && $quarters->file != null){
											   $file_old = $path.'/'.$quarters->file;
											   unlink($file_old);
										  }

										  //upload new file
										  $file = $request->file[$key];
										  $filename = $file->getClientOriginalName();
										  $file->move($path, $filename);
										  
										  $quarters->file = $filename;
									 }
	 
									 
									$quarters->save(); 
								}
								else
								{
									if(isset($request->file[$key] )){        
										$path = 'public/images/experience/'.$property_id;
										  //upload new file
										  $file = $request->file[$key];
										  $filename = $file->getClientOriginalName();
										  $file->move($path, $filename);
									 }
									 else
									 {
										 $filename="";
									 }
									 
									DB::table('family_package')->insert(
									 array(
											'property_id'     => $property_id, 
											'title'      	  => $request->title[$key],
											'file'            => $filename,
											'price'    	 	  => $request->fprice[$key],
											'adults'    	  => $request->adults[$key],
											'children'        => $request->children[$key],
											'infants'         => $request->infants[$key],
											'itinerary'       => $request->itinerary[$key],
									 )
									); 
								}
							}
						}
						
					}

                    return redirect('admin/experience/'.$property_id.'/booking');
                }
            }
            $data['itinerary']  = DB::table('sv_experience_itinerary')->where('property_id', $property_id)->get();
			$data['time']  		= DB::table('property_time')->where('property_id', $property_id)->get();
			$data['family']		= DB::table('family_package')->where('property_id', $property_id)->get();
        } elseif ($step == 'booking') {
            if ($request->isMethod('post')) {
                $properties               = Properties::find($property_id);
                $properties->booking_type = $request->booking_type;
				$properties->cancellation = $request->cancellation;
                $properties->status       = 'Listed';
                //$properties->admin_approval = 1;
                $properties->save();

                $property_steps          = PropertySteps::where('property_id', $property_id)->first();
                $property_steps->booking = 1;
                $property_steps->save();
                
                return redirect('admin/experience/'.$property_id.'/calender');
            }
        } elseif ($step == 'calender') {
            $data['calendar'] = $calendar->generate($request->id);
        }

        return view("admin.experience.$step", $data);
    }

    public function update(Request $request)
    {
        if (! $request->isMethod('post')) {
              $amenity_type=AmenityType::get();
              $am = [];
            foreach ($amenity_type as $key => $value) {
                $am[$value->id]=$value->name;
            }
              $data['am'] = $am;
            $data['result'] = Amenities::find($request->id);
            return view('admin.amenities.edit', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                    'title'          => 'required',
                    'description'    => 'required',
                    'symbol'         => 'required',
                    'type_id'        => 'required',
                    'status'         => 'required'

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
                $amenitie->title          = $request->title;
                $amenitie->description    = $request->description;
                $amenitie->symbol         = $request->symbol;
                $amenitie->type_id        = $request->type_id;
                $amenitie->status         = $request->status;
                $amenitie->save();

                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/amenities');
            }
        }
    }
    
    public function delete(Request $request)
    {
        $bookings   = Bookings::where(['property_id' => $request->id])->get()->toArray();
        if (env('APP_MODE', '') != 'test') {
            if (count($bookings) > 0) {
               $this->helper->one_time_message('danger', 'This Experience has Bookings.Sorry can not possible to delete'); 
            } else {
                //Properties::find($request->id)->delete();
                
                 Properties::where(['id' => $request->id])->update(['deleted_status' => 'Yes']);

                $this->helper->one_time_message('success', 'Deleted Successfully');
                return redirect('admin/experience');                    
             } 
         }
         return redirect('admin/experience');  
    }

    public function photoMessage(Request $request)
    {
           $property = Properties::find($request->id);
            $photos  = PropertyPhotos::find($request->photo_id);
            $photos->message = $request->messages;
            $photos->save();
        
        return json_encode(['success'=>'true']);
    }

    public function photoDelete(Request $request)
    {
      
            $property = Properties::find($request->id);
            $photos   = PropertyPhotos::find($request->photo_id);
            $photos->delete();
        
        return json_encode(['success'=>'true']);
    }

    public function makeDefaultPhoto(Request $request)
    {

        if ($request->option_value == 'Yes') {
            PropertyPhotos::where('property_id', '=', $request->property_id)
            ->update(['cover_photo' => 0]);

            $photos = PropertyPhotos::find($request->photo_id);
            $photos->cover_photo = 1;
            $photos->save();
        }
        return json_encode(['success'=>'true']);
    }

    public function makePhotoSerial(Request $request)
    {
       
        $photos         = PropertyPhotos::find($request->id);
        $photos->serial = $request->serial;
        $photos->save();

        return json_encode(['success'=>'true']);
    }

    public function propertyCsv($id = null)
    {
        return Excel::download(new PropertiesExport, 'properties_sheet' . time() .'.xls');
    }

    public function propertyPdf($id = null)
    {
        $to                 = setDateForDb(request()->to);
        $from               = setDateForDb(request()->from);
        $data['companyLogo']  = $logo  = Settings::where('name', 'logo')->select('value')->first();
        if ($logo->value==null) {
            $data['logo_flag'] = 0;
        } elseif (! file_exists("public/front/images/logos/$logo->value")) {
            $data['logo_flag'] = 0;
        }
        $data['status']     = $status = isset(request()->status) ? request()->status : null;
        $data['space_type'] = $space_type = isset(request()->space_type) ? request()->space_type : 'null';
        $properties = $this->getAllProperties();

        if (isset($id)) {
            $properties->where('properties.host_id', '=', $id);
        }

        if ($from) {
            $properties->whereDate('properties.created_at', '>=', $from);
        }

        if ($to) {
                $properties->whereDate('properties.created_at', '<=', $to);
        }

        if (!is_null($status)) {
                $properties->where('properties.status', '=', $status);
        }

        $data['propertyList'] = $properties->get();
        
        if ($space_type) {
                $properties->where('properties.space_type', '=', $space_type);
        }
        if ($from && $to) {
            $data['date_range'] = onlyFormat($from) . ' To ' . onlyFormat($to);
        }
        

        $pdf = PDF::loadView('admin.properties.list_pdf', $data, [], [
            'format' => 'A3', [750, 1060]
          ]);
        return $pdf->download('property_list_' . time() . '.pdf', array("Attachment" => 0));
    }

    public function getAllProperties()
    {
        $query = Properties::join('users', function ($join) {
            $join->on('users.id', '=', 'properties.host_id');
        })
            ->join('space_type', function ($join) {
                    $join->on('space_type.id', '=', 'properties.space_type');
            })

            ->select(['properties.id as properties_id', 'properties.name as property_name', 'properties.status as property_status', 'properties.created_at as property_created_at', 'properties.updated_at as property_updated_at','space_type.name as Space_type_name' , 'properties.*', 'users.*', 'space_type.*'])
            ->orderBy('properties.id', 'desc');
            return $query;
    }
    public function change_status($id,$status)
	{
		Properties::where('id', $id)
       ->update([
           'admin_approval' => $status
        ]);
        
		return back();
	}
	
	/* experience category */
	public function cat_index(ExperienceCategoryDataTable $dataTable)
    {
		return $dataTable->render('admin.experience.category');
    }
	public function cat_add(Request $request)
    {
        if (! $request->isMethod('post')) 
		{
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.experience.add_category', $data);
        } 
		elseif ($request->isMethod('post'))
		{
            $rules = array(
                  /*   'name'           => 'required|max:100',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
             	unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'experience_category'");
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
					$newTemplate 			= new ExperienceCategory;
					$newTemplate->temp_id   = $temp_id;
                        
					if(isset($data[$i]['name']))
					{
						$newTemplate->name  	= $data[$i]['name'];
					}
						
					$newTemplate->lang      = $lang[$i];
					$newTemplate->lang_id   = $lang_id[$i];
						                        
					if(isset($data[$i]['status']))
					{
						$newTemplate->status    = $data[$i]['status'];
					}
					$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/experience/experience_category');
            }
        }
    }
	
	public function cat_update(Request $request)
    {
        if (! $request->isMethod('post')) {
            $data['result']	   = ExperienceCategory::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.experience.edit_category', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /*  'name'           => 'required|max:110',
                    'description'    => 'required|max:255',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $experienceCategory  = ExperienceCategory::find($request->id);
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = ExperienceCategory::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					if ($check) 
					{
						$templateToUpdate = ExperienceCategory::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						$templateToUpdate->save();
					}
					else 
					{
						$newTemplate 			= new ExperienceCategory;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  = $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}
                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/experience/experience_category');
            }
        }
    }
	public function cat_delete(Request $request)
    {
		//ExperienceCategory::where('temp_id',$request->id)->delete();
         ExperienceCategory::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status' => 'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/experience/experience_category');
    }
	
	/* Inclusion category */
	public function inclusion_index(InclusionDataTable $dataTable)
    {
		return $dataTable->render('admin.experience.inclusion');
    }
	public function inclusion_add(Request $request)
    {
        if (! $request->isMethod('post')) 
		{
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.experience.add_inclusion', $data);
        } 
		elseif ($request->isMethod('post'))
		{
            $rules = array(
                  /* 'name'           => 'required|max:100',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
             	unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'inclusion'");
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
					$newTemplate 			= new Inclusion;
					$newTemplate->temp_id   = $temp_id;
                        
					if(isset($data[$i]['name']))
					{
						$newTemplate->name  	= $data[$i]['name'];
					}
						
					$newTemplate->lang      = $lang[$i];
					$newTemplate->lang_id   = $lang_id[$i];
						                        
					if(isset($data[$i]['status']))
					{
						$newTemplate->status    = $data[$i]['status'];
					}
					$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/experience/inclusion');
            }
        }
    }
	
	public function inclusion_update(Request $request)
    {
        if (! $request->isMethod('post')) {
            $data['result']	   = Inclusion::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.experience.edit_inclusion', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /*  'name'           => 'required|max:110',
                    'description'    => 'required|max:255',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $inclusion  = Inclusion::find($request->id);
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = Inclusion::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					if ($check) 
					{
						$templateToUpdate = Inclusion::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						$templateToUpdate->save();
					}
					else 
					{
						$newTemplate 			= new Inclusion;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  = $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}
                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/experience/inclusion');
            }
        }
    }
	public function inclusion_delete(Request $request)
    {
		//Inclusion::where('temp_id',$request->id)->delete();
          Inclusion::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status' => 'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/experience/inclusion');
    }
	
	/* Exclusion */
	public function exclusion_index(ExclusionDataTable $dataTable)
    {
		return $dataTable->render('admin.experience.exclusion');
    }
	public function exclusion_add(Request $request)
    {
        if (! $request->isMethod('post')) 
		{
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();
            return view('admin.experience.add_exclusion', $data);
        } 
		elseif ($request->isMethod('post'))
		{
            $rules = array(
                  /* 'name'           => 'required|max:100',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
             	unset($request['_token']);
				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
				
				$db_name = env('DB_DATABASE');
				
				$table = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_name."' AND TABLE_NAME = 'exclusion'");
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
					$newTemplate 			= new Exclusion;
					$newTemplate->temp_id   = $temp_id;
                        
					if(isset($data[$i]['name']))
					{
						$newTemplate->name  	= $data[$i]['name'];
					}
						
					$newTemplate->lang      = $lang[$i];
					$newTemplate->lang_id   = $lang_id[$i];
						                        
					if(isset($data[$i]['status']))
					{
						$newTemplate->status    = $data[$i]['status'];
					}
					$newTemplate->save();
				}

                $this->helper->one_time_message('success', 'Added Successfully');
                return redirect('admin/experience/exclusion');
            }
        }
    }
	
	public function exclusion_update(Request $request)
    {
        if (! $request->isMethod('post')) {
            $data['result']	   = Exclusion::find($request->id);
			$data['languages'] = Language::where(['language.status'=>'Active'])->orderBy('id')->get();

            return view('admin.experience.edit_exclusion', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                   /*  'name'           => 'required|max:110',
                    'description'    => 'required|max:255',
                    'status'         => 'required' */
                    );

            $fieldNames = array(
                        'name'              => 'Name',
                        'status'            => 'Status'
                        );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $exclusion  = Exclusion::find($request->id);
				unset($request['_token']);

				$id=$request->id;
				foreach ($request->all() as $key => $value) {
					$lang[] 	= $key; 
					$lang_id[]  = $value['id'];
					unset($value['id']);
					$data[]     = $value;
				}
		
				for ($i=0; $i < count($lang_id); $i++) {
					$check = Exclusion::where('lang_id', '=', $lang_id[$i])
											->where('temp_id', '=', $id)
											->first();
					if ($check) 
					{
						$templateToUpdate = Exclusion::where([['temp_id',$id],['lang_id', $lang_id[$i]]])->first();
						if(isset($data[$i]['name']))
						{
							$templateToUpdate->name  	= $data[$i]['name'];
						}
						if(isset($data[$i]['status']))
						{
							$templateToUpdate->status   = $data[$i]['status'];
						}
						$templateToUpdate->save();
					}
					else 
					{
						$newTemplate 			= new Exclusion;
						$newTemplate->temp_id   = $id;
						if(isset($data[$i]['name']))
						{
							$newTemplate->name  = $data[$i]['name'];
						}
						$newTemplate->lang      = $lang[$i];
						
						$newTemplate->status    = $data[$i]['status']; 
						$newTemplate->lang_id   = $lang_id[$i];
						$newTemplate->save(); 
					}
				}
                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/experience/exclusion');
            }
        }
    }
	public function exclusion_delete(Request $request)
    {
		//Exclusion::where('temp_id',$request->id)->delete();
          Exclusion::where(['temp_id' => $request->id])->update(['deleted_status' => 'Yes', 'status' => 'Inactive' ]);

        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/experience/exclusion');
    }
    
}
