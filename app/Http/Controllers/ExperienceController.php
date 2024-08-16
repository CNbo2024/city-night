<?php

namespace App\Http\Controllers;

use Cache;
use Auth;
use DB;
use Session;

use App\Http\Helpers\Common;
use App\Http\Helpers\Random;
use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Wishlist;
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
    public function __construct()
    {
        $this->helper = new Common;
       // $this->exp = new Random;
    }

    public function userexperience(Request $request)
    {   
        switch ($request->status) {    
            case 'Listed':
            case 'Unlisted':
                $pram = [['status', '=', $request->status]];
                break;            
            default:
                $pram = [];
                break;
        }

        $data['status'] = $request->status;
        $data['properties'] = Properties::with('property_price', 'property_address')
                                ->where('host_id', Auth::id())
								->where('type', 'experience')
								->where('deleted_status', 'No')
                                ->where($pram)
                                ->orderBy('id', 'desc')
                                ->paginate(Session::get('row_per_page'));
		 $data['title'] = "Experience";
        return view('experience.listings', $data);
    }
    
    public function create(Request $request)
    {
		$current_lang = Session::get('language');
        if ($request->isMethod('post')) {
            $rules = array(
                'map_address'       => 'required',
            );

            $fieldNames = array(
                'map_address'       => 'City',
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $property                  = new Properties;
                $property->host_id         = Auth::id();
				$property->type            = "experience";
				$property->property_type   = "1";
				$property->space_type      = "1";
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

                return redirect('experience/'.$property->id.'/basics');
            }
        }

        $data['title'] 		   = "Add new Experience";
       /// $typpp = $this->exp->get_property_type();
	//	if($typpp == "success")
    	{
    	//    $typpp1 = $this->exp->get_space_type();
    	}
        return view('experience.create', $data);
    }

    public function listing(Request $request, CalendarController $calendar)
    {
		$current_lang = Session::get('language');

        $step            = $request->step;
        $property_id     = $request->id;
        $data['step']    = $step;
        $data['result']  = Properties::where('host_id', Auth::id())->findOrFail($property_id);
        $data['details'] = PropertyDetails::pluck('value', 'field');
        $data['missed']  = PropertySteps::where('property_id', $request->id)->first();

        
        if ($step == 'basics') {
            if ($request->isMethod('post')) {
				
                $property                     = Properties::find($property_id);
                $property->experience_type    = $request->experience_type;
                $property->name         	  = $request->name;
                $property->slug     		  = $this->helper->pretty_url($request->name);
                $property->accommodates       = $request->accommodates;
				$property->duration       	  = $request->duration;
				//$property->itinerary        = $request->field_name;
                $property->save();

                $property_steps         = PropertySteps::where('property_id', $property_id)->first();
                $property_steps->basics = 1;
                $property_steps->save();
				
                return redirect('experience/'.$property_id.'/description');
            }
			$data['title']  	= "Basics";
			$data['category']   = ExperienceCategory::where('status', 'Active')->where('lang', $current_lang)->where('deleted_status', 'No')->get();


        } elseif ($step == 'description') {
            if ($request->isMethod('post')) {
                
                $rules = array(
                    'name'     => 'required|max:50',
                    'summary'  => 'required|max:1000'
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

                    $property_steps              = PropertySteps::where('property_id', $property_id)->first();
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
					
                 
                  return redirect('experience/'.$property_id.'/details');
				  
                }
            }
            $data['description']       = PropertyDescription::where('property_id', $property_id)->first();
			$data['languages_new'] 	   = Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 
			$data['title']             = "Description";

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

                return redirect('experience/'.$property_id.'/location');
            }
			$data['languages_new'] 		   = Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 
			 $data['title']                = "Details";

        } elseif ($step == 'location') {
            if ($request->isMethod('post')) {
                $rules = array(
                    'address_line_1'    => 'required|max:250',
                    'address_line_2'    => 'max:250',
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

                    $property_steps           = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->location = 1;
                    $property_steps->save();

                    return redirect('experience/'.$property_id.'/amenities');
                }
            }
            $data['country']       = Country::pluck('name', 'short_name');
             $data['title'] = "Location";
        } elseif ($step == 'amenities') {
            if ($request->isMethod('post') ) 
			{
                $rooms            = Properties::find($request->id);
                if(isset($request->exclusion))
                {
                    $rooms->exclusion = implode(',', $request->exclusion);
                }
                if(isset($request->inclusion))
                {
				    $rooms->inclusion = implode(',', $request->inclusion);
                }
				$rooms->amenities = '0';
                $rooms->save();
                return redirect('experience/'.$property_id.'/photos');
            }
            $data['property_exclusion'] = explode(',', $data['result']->exclusion);
			$data['property_inclusion'] = explode(',', $data['result']->inclusion);
            $data['inclusion']          = Inclusion::where('status', 'Active')->where('lang', $current_lang)->where('deleted_status', 'No')->get();
            $data['exclusion']          = Exclusion::where('status', 'Active')->where('lang', $current_lang)->where('deleted_status', 'No')->get();
			$data['title'] = "Inclusion/Exclusion";
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
                     return redirect('experience/'.$property_id.'/photos')->with('success', trans('messages.experience.file_uploaded_successfully'));

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
                       return redirect('experience/'.$property_id.'/photos')->with('success', trans('messages.experience.file_uploaded_successfully'));

                    }
                    if(empty($request->photo)){
                       return redirect('listing/'.$property_id.'/photos')->with('danger', 'Please upload atleast one photo!'); 
                    }
                    
                    return redirect('listing/'.$property_id.'/photos')->with('success', 'File Uploaded Successfully!');
            }
            
            $data['photos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','photo')
                                ->orderBy('serial', 'asc')
                                ->get();
            $data['videos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','video')
                                ->orderBy('serial', 'asc')
                                ->get();

             $data['title'] = "Photos";

        } elseif ($step == 'pricing') {
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
                    $property_price->currency_code     = $request->currency_code;
                    $property_price->save();

                    $property_steps = PropertySteps::where('property_id', $property_id)->first();
                    $property_steps->pricing = 1;
                    $property_steps->save();
					
					$properties              	  = Properties::find($property_id);
					$properties->exp_booking_type = $request->exp_booking_type;
					$properties->save();
					
					/* if(isset($request->field_name))
					{
						DB::table('sv_experience_itinerary')->where('property_id', $property_id)->delete();
						for($i = 0; $i < count($request->field_name); $i++)
						{
							$field_name  = $_POST['field_name'][$i];
							$description = $_POST['description'][$i];
							DB::table('sv_experience_itinerary')->insert(
								 array(
										'property_id'     => $property_id, 
										'title'           => $field_name,
										'description'     => $description
								 )
							);
						}
					} */
					if($request->exp_booking_type=="2")
					{
						/* if(isset($request->start_time))
						{
							DB::table('property_time')->where('property_id', $property_id)->delete();
							for($i = 0; $i < count($request->start_time); $i++)
							{
								$start_time  = $_POST['start_time'][$i];
								$end_time	 = $_POST['end_time'][$i];
								DB::table('property_time')->insert(
									 array(
											'property_id'     => $property_id, 
											'start_time'      => $start_time,
											'end_time'    	  => $end_time
									 )
								);
							}
						} */
						
						
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
									$quarters->full_details = $request->full_details[$key];
									
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
											'full_details'    => $request->full_details[$key],
									 )
									); 
								}
							}
						}
						
						/* if(isset($request->title))
						{
							DB::table('family_package')->where('property_id', $property_id)->delete();
							for($i = 0; $i < count($request->title); $i++)
							{
								$title   	= $_POST['title'][$i];
								$fprice	 	= $_POST['fprice'][$i];
								$adults	 	= $_POST['adults'][$i];
								$children	= $_POST['children'][$i];
								$infants	= $_POST['infants'][$i];
								$details	 = $_POST['details'][$i];
								DB::table('family_package')->insert(
									 array(
											'property_id'     => $property_id, 
											'title'      	  => $title,
											'price'    	 	  => $fprice,
											'adults'    	  => $adults,
											'children'    	  => $children,
											'infants'    	  => $infants,
											'details'    	  => $details,
									 )
								);
							}
						} */
					}
                    return redirect('experience/'.$property_id.'/booking');
                }
				
            }
			$data['itinerary']  = DB::table('sv_experience_itinerary')->where('property_id', $property_id)->get();
			$data['time']  		= DB::table('property_time')->where('property_id', $property_id)->get();
			$data['family']		= DB::table('family_package')->where('property_id', $property_id)->get();
			 $data['title']     = "Pricing";
        } elseif ($step == 'booking') {
            if ($request->isMethod('post')) {
               
               $settings = DB::table('settings')
                               ->where('id', '=', 45)
                			   ->where('name','=','auto_approval')
                               ->get();

                $property_steps          = PropertySteps::where('property_id', $property_id)->first();
                $property_steps->booking = 1;
                $property_steps->save();
                          
                $properties               = Properties::find($property_id);
                $properties->booking_type = $request->booking_type;
				$properties->cancellation = $request->cancellation;
				
                $properties->status       = ( $properties->steps_completed == 0 ) ?  'Listed' : 'Unlisted';
                
                if($settings[0]->value=="yes")
				{
				    $properties->admin_approval = 1;
				}
				else if($settings[0]->value=="no")
				{
					$properties->admin_approval = 0;
				}
                $properties->save();
                return redirect('experience/'.$property_id.'/calendar');
            }
            $data['title'] = "Booking";
        } elseif ($step == 'calendar') {
            $data['calendar'] = $calendar->generate($request->id);
            $data['title'] = "Calendar";
        }
        
        return view("experience.$step", $data);
    }


    public function updateStatus(Request $request)
    {
        $property_id = $request->id;
        $reqstatus = $request->status;
        if ($reqstatus == 'Listed') {
            $status = 'Unlisted';
        }else{
            $status = 'Listed';
        }
        $properties         = Properties::where('host_id', Auth::id())->find($property_id);
        $properties->status = $status;
        $properties->save();
        return  response()->json($properties);

    }

    public function getPrice(Request $request)
    {
        return $this->helper->get_price($request->property_id, $request->checkin, $request->checkout, $request->guest_count);
    }

    public function single(Request $request)
    {
        
        $current_lang = Session::get('language');

        $data['property_slug'] = $request->slug;

        $data['result'] = $result = Properties::where('slug', $request->slug)->first();   
        
         if ( empty($result)  ) {
            abort('404');
        }

		$data['other_lang'] 	  = PropertyMeta::where('lang', $current_lang)->where('property_id',$result->id)->first();
		
         $data['property_id'] = $id = $result->id;

        $data['property_photos']     = PropertyPhotos::where('property_id', $id)->where('type','photo')->orderBy('serial', 'asc')->get();
        
        $data['property_videos']     = PropertyPhotos::where('property_id', $id)->where('type','video')->orderBy('serial', 'asc')->get();
                                            
        $data['amenities']        = Amenities::normal($id);
        $data['safety_amenities'] = Amenities::security($id);
        
        $property_address         = $data['result']->property_address;
        $property_address         = $data['result']->property_address;
       
        $latitude                 = $property_address->latitude;
        
        $longitude                = $property_address->longitude;

        $data['checkin']          = (isset($request->checkin) && $request->checkin != '') ? $request->checkin:'';
        $data['checkout']         = (isset($request->checkout) && $request->checkout != '') ? $request->checkout:'';
        
        $data['guests']           = (isset($request->guests) && $request->guests != '')?$request->guests:'';

        $data['similar']  = Properties::join('property_address', function ($join) {
                                        $join->on('properties.id', '=', 'property_address.property_id');
									})
								    ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
                                    ->having('distance', '<=', 30)
                                    ->where('properties.id', '!=', $id)
                                    ->where('properties.status', 'Listed')
                                    ->where('properties.admin_approval', '1')
                                    ->get();
        $data['title']    =   $data['result']->name.' in '.$data['result']->property_address->city;
       
        $data['shareLink'] = url('/').'/'.'properties/'.$data['property_id'];

        return view('property.single', $data);
    }

    public function currencySymbol(Request $request)
    {
        $symbol          = Currency::code_to_symbol($request->currency);
        $data['success'] = 1;
        $data['symbol']  = $symbol;

        return json_encode($data);
    }

    public function photoMessage(Request $request)
    {
        $property = Properties::find($request->id);
        if ($property->host_id == \Auth::user()->id) {
            $photos = PropertyPhotos::find($request->photo_id);
            $photos->message = $request->messages;
            $photos->save();
        }
        
        return json_encode(['success'=>'true']);
    }

    public function photoDelete(Request $request)
    {
        $property   = Properties::find($request->id);
        if ($property->host_id == \Auth::user()->id) {
            $photos = PropertyPhotos::find($request->photo_id);
            $photos->delete();
        }
        
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


    public function set_slug()
    {

       $properties   = Properties::where('slug', NULL)->get();
       foreach ($properties as $key => $property) {

           $property->slug     = $this->helper->pretty_url($property->name);
           $property->save();
       }
       return redirect('/');

    }
    
     public function wishlist(Request $request){
        $propertyid = $request->wishid;
        DB::table('wishlist')->where('propertyid', $propertyid)->delete();
        if($propertyid!='') {
			DB::table('wishlist')->insert(array( 'propertyid' => $propertyid,'userid' => \Auth::user()->id ,'status' => '1'    )
			);
        }

    }
    public function wishlistremove(Request $request){
        $propertyid = $request->wishid;
        DB::table('wishlist')
            ->where('propertyid', $propertyid)
            ->where('userid','=',  \Auth::user()->id )
            ->update(['status' => 0]);
    }
    public function deletepackage(Request $request){
        $id = $request->wishid;
        DB::table('family_package')->where('id', $id)->delete();
    }
    
    public function deletepackagetime(Request $request){
        $id = $request->wishid;
        DB::table('property_time')->where('id', $id)->delete();
    }
    
    public function updateqty(Request $request)
    {   
        $package_id  = $request->package_id;
        $qty         = $request->qty;
        $property_id = $request->property_id;
        
        $product = FamilyPackage::findOrFail($package_id);
        $cart    = session()->get('cart', []);

        $cart[$package_id] = [
            "name"      => $product->title,
            "quantity"  => $qty,
            "price"     => $product->price,
            "property_id" => $property_id,
        ];
        
        session()->put('cart', $cart);
        //Session::forget('cart');
       
        $data = "success";
         return $this->helper->get_price($property_id, $request->checkin, $request->checkout, $request->guest);
        //return response()->json($data);
    }
    
    public function remove_cart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            $data = "success";
            return $this->helper->get_price( $request->property_id, $request->checkin, $request->checkout, $request->guest);
            
            //return response()->json($data);
        }
        
    }

}
