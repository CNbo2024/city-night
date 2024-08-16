<?php

namespace App\Http\Controllers;

use Cache;
use Auth;
use DB;
use Session;

use Illuminate\Support\Arr;

use App\Http\Helpers\Common;
use App\Http\Helpers\Random;
use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Wishlist;
use App\Models\{
	User,
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
	Bookings
};



class PropertyController extends Controller
{
    public function __construct()
    {
        $this->helper = new Common;
       // $this->sangproperty = new Random;
    }

    public function userProperties(Request $request)
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

        $users = User::where('cohosts', 'LIKE', '%' . auth()->user()->email . '%')->get()->pluck('id')->toArray();

        $data['status'] = $request->status;

        $data['properties'] = Properties::with('property_price', 'property_address')
            ->where('host_id', Auth::id())
			->orWhere(function ($query) use ($users) {
				$query->whereIn('host_id', $users);
			})
			->where('type', 'property')
			->where('deleted_status', 'No')
            ->where($pram)
            ->orderBy('id', 'desc')
            ->paginate(Session::get('row_per_page'));

        if ($request->status == 'Posted by me') {
        	$data['properties'] = Properties::with('property_price', 'property_address')
	            ->where('host_id', Auth::id())
				->where('type', 'property')
				->where('deleted_status', 'No')
	            ->where($pram)
	            ->orderBy('id', 'desc')
	            ->paginate(Session::get('row_per_page'));
        }

        if ($request->status == 'Managed by me') {
        	$data['properties'] = Properties::with('property_price', 'property_address')
	            ->orWhere(function ($query) use ($users) {
					$query->whereIn('host_id', $users);
				})
				->where('type', 'property')
				->where('deleted_status', 'No')
	            ->where($pram)
	            ->orderBy('id', 'desc')
	            ->paginate(Session::get('row_per_page'));
        }

        return view('property.listings', $data);
    }

    public function cohosts(Request $request)
    {
    	$cohosts = explode("\r\n", $request->cohosts);
    	$cohosts = implode(',', $cohosts);

    	$user = User::find($request->user_id);
    	$user->cohosts = $cohosts;
    	$user->save();

    	$this->helper->one_time_message('success', trans('Co-hosts updated successfully!'));
    	return redirect('/properties');
    }

    public function create(Request $request)
    {
		$current_lang = Session::get('language');
        if ($request->isMethod('post')) {
            $rules = array(
                'property_type_id'  => 'required',
                'space_type'        => 'required',
                'accommodates'      => 'required',
                'map_address'       => 'required',
            );

            $fieldNames = array(
                'property_type_id'  => 'Home Type',
                'space_type'        => 'Room Type',
                'accommodates'      => 'Accommodates',
                'map_address'       => 'City',
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $property                  = new Properties;
                $property->host_id         = Auth::id();
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

                return redirect('listing/'.$property->id.'/basics');
            }
        }

        $data['property_type'] = PropertyType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');
        $data['space_type']    = SpaceType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');
        
        //$sangvish = $this->sangproperty->get_unique_id();
	//	if($sangvish == "success")
    	{ 
    	  //  $sangvish1 = $this->sangproperty->check_steps();
    	}
        
        return view('property.create', $data);
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
                $property->bedrooms           = $request->bedrooms;
                $property->beds               = $request->beds;
                $property->bathrooms          = $request->bathrooms;
                $property->bed_type           = $request->bed_type;
                $property->property_type      = $request->property_type;
                $property->space_type         = $request->space_type;
                $property->accommodates       = $request->accommodates;
                $property->save();

                $property_steps         = PropertySteps::where('property_id', $property_id)->first();
                $property_steps->basics = 1;
                $property_steps->save();
                return redirect('listing/'.$property_id.'/description');
            }

            $data['bed_type']       = BedType::where('lang', $current_lang)->where('deleted_status', 'No')->pluck('name', 'id');
            $data['property_type']  = PropertyType::where('status', 'Active')->where('lang', $current_lang)->where('deleted_status', 'No')->pluck('name', 'id');;
            $data['space_type']     = SpaceType::where('lang', $current_lang)->where('deleted_status', 'No')->pluck('name', 'id');
        } elseif ($step == 'description') {
            if ($request->isMethod('post')) {

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
					
                  //return redirect('listing/'.$property_id.'/location');
                  
                  return redirect('listing/'.$property_id.'/details');
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


               // return redirect('listing/'.$property_id.'/description');
                return redirect('listing/'.$property_id.'/location');
            }
			$data['languages_new'] 		   = Language::where(['language.status'=>'Active'])->orderBy('id')->get(); 

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

                    return redirect('listing/'.$property_id.'/amenities');
                }
            }
            $data['country']       = Country::pluck('name', 'short_name');
        } elseif ($step == 'amenities') {
            if ($request->isMethod('post') && is_array($request->amenities)) {
                $rooms            = Properties::find($request->id);
                $rooms->amenities = implode(',', $request->amenities);
                $rooms->save();
                return redirect('listing/'.$property_id.'/photos');
            }
            $data['property_amenities'] = explode(',', $data['result']->amenities);
            $data['amenities']          = Amenities::where('status', 'Active')->where('deleted_status', 'No')->get();
            $data['amenities_type']     = AmenityType::where('lang', $current_lang)->where('deleted_status', 'No')->get();
        } elseif ($step == 'photos') {
            
            if ($_FILES) {
                
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
                        return redirect('listing/'.$property_id.'/photos')->with('success', trans('messages.experience.file_uploaded_successfully'));
                    }
                    
                     if(empty($request->photo)){
                       return redirect('listing/'.$property_id.'/photos')->with('danger', 'Please upload atleast one photo!'); 
                    }
                    

                    return redirect('listing/'.$property_id.'/photos')->with('success', 'File Uploaded Successfully!');
                    
                }
            }
            
            $data['photos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','photo')
                                ->orderBy('serial', 'asc')
                                ->get();
            $data['videos']    = PropertyPhotos::where('property_id', $property_id)
                                ->where('type','video')
                                ->orderBy('serial', 'asc')
                                ->get();
                                
        } elseif ($step == 'pricing') {
            if ($request->isMethod('post')) {
                $rules = array(
                    'price' => 'required|numeric|min:5',
                    'weekly_discount' => 'nullable|numeric|max:99|min:0',
                    'monthly_discount' => 'nullable|numeric|max:99|min:0'
                );
            
                $fieldNames = array(
                    'price'  => 'Price',
                    'weekly_discount' => 'Weekly Discount Percent',
                    'monthly_discount' => 'Monthly Discount Percent'
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

                    return redirect('listing/'.$property_id.'/booking');
                }
            }
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
				$properties->check_in_after = $request->check_in_after;
				$properties->check_out_before = $request->check_out_before;
				
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

                
                return redirect('listing/'.$property_id.'/calendar');
            }
        } elseif ($step == 'calendar') {
            $data['calendar'] = $calendar->generate($request->id);
        }

        unset($data['languages_new'][1]);
        unset($data['languages_new'][2]);
        unset($data['languages_new'][3]);
        unset($data['languages_new'][4]);
        unset($data['languages_new'][5]);
        unset($data['languages_new'][7]);

        return view("listing.$step", $data);
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
    	$dates = session('dates') ?? [];
    	$dates[$request->property_id] = ['checkin' => $request->checkin, 'checkout' => $request->checkout];
    	session()->put('dates', $dates);

        return $this->helper->get_price($request->property_id, $request->checkin, $request->checkout, $request->guest_count, $request->time_slot);
    }

    public function single(Request $request)
    {
    	$data['dates'] = Arr::dot(session()->all()['dates'] ?? []);

        $current_lang = Session::get('language');
		if($current_lang=="")
		{
			$current_lang = "en";
		}
		else
		{
			$current_lang = $current_lang;
		}

        $data['property_slug'] = $request->slug;
        $data['property_id']   = $request->id;
        //$data['result'] = $result = Properties::where('slug', $request->slug)->where('deleted_status', 'No')->first();
        
        $data['result'] = $result = Properties::where('id', $request->id)->where('deleted_status', 'No')->first();  
        
        if ( empty($result)  ) {
            abort('404');
        }

		$data['other_lang'] 	  = PropertyMeta::where('lang', $current_lang)->where('property_id',$result->id)->first();
		
        $data['property_id'] = $id = $result->id;
        
         $curr_date = date('Y-m-d');
  
 		$data['category']       = ExperienceCategory::where('lang', $current_lang)->where('temp_id',$result->experience_type)->first();

        $data['property_photos'] = PropertyPhotos::where('property_id', $id)->where('type','photo')->orderBy('serial', 'asc')->get();
        
        $data['property_videos']  = PropertyPhotos::where('property_id', $id)->where('type','video')->orderBy('serial', 'asc')->get();
                                            
        $data['amenities']        = Amenities::normal($id);
        $data['safety_amenities'] = Amenities::security($id);
        
        $property_address         = $data['result']->property_address;
        $property_address         = $data['result']->property_address;
       
        $latitude                 = $property_address->latitude;
        
        $longitude                = $property_address->longitude;

        $data['checkin']          = (isset($request->checkin) && $request->checkin != '') ? $request->checkin:'';
        $data['checkout']         = (isset($request->checkout) && $request->checkout != '') ? $request->checkout:'';
        
        $data['guests']           = (isset($request->guests) && $request->guests != '')?$request->guests:'';


        if($data['result']->type=="experience")
        {
            $data['similar']  = Properties::join('property_address', function ($join) {
                                            $join->on('properties.id', '=', 'property_address.property_id');
    									})
    								    ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
                                        ->having('distance', '<=', 30)
                                        ->where('properties.id', '!=', $id)
                                        ->where('properties.status', 'Listed')
                                        ->where('properties.type', 'experience')
                                        ->where('properties.admin_approval', '1')
                                        ->where('properties.deleted_status', 'No')
                                        ->get();
        }
        if($data['result']->type=="property")
        {
            $data['similar']  = Properties::join('property_address', function ($join) {
                                            $join->on('properties.id', '=', 'property_address.property_id');
    									})
    								    ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
                                        ->having('distance', '<=', 30)
                                        ->where('properties.id', '!=', $id)
                                        ->where('properties.status', 'Listed')
                                        ->where('properties.type', 'property')
                                        ->where('properties.admin_approval', '1')
                                        ->where('properties.deleted_status', 'No')
                                        ->get();
        }    

        $data['title']    =   $data['result']->name.' in '.$data['result']->property_address->city;
       
        $data['shareLink'] = url('/').'/'.'properties/'.$data['property_id'];
		
		$data['itinerary']  = DB::table('sv_experience_itinerary')->where('property_id', $id)->get();
		$inclusion_id 		= $result->inclusion;
		$data['inclusion']  = Inclusion::where('lang', $current_lang)->get();
	    $data['property_inclusion'] = explode(',', $inclusion_id);

		
		$exclusion_id 		= $result->exclusion;
		$data['exclusion']  = Exclusion::where('lang', $current_lang)->get();
	    $data['property_exclusion'] = explode(',', $exclusion_id);
		$data['time']    = DB::table('property_time')->where('property_id', $id)->get();
		$data['family']  = DB::table('family_package')->where('property_id', $id)->get();
        //Session::forget('cart');
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
    
    
    public function duplicate($property_id)
    {
         $original_property = Properties::find($property_id);
		
        $duplicate_property = $original_property->replicate();
        $duplicate_property->status = 'Unlisted';
        $duplicate_property->admin_approval = '0';
        $duplicate_property->slug = $this->helper->pretty_url1($original_property->name);
        $duplicate_property->save();       

		
		 //Property address duplicate 
        $org_address = PropertyAddress::where('property_id',$property_id)->first();
        $duplicate_address = $org_address->replicate();
        $duplicate_address->property_id = $duplicate_property->id;
        $duplicate_address->save(); 
        
		//property price duplicate
        $org_price = PropertyPrice::find($property_id);
        if(isset($org_price))
        {
            $dup_room_price = $org_price->replicate();
            $dup_room_price->property_id = $duplicate_property->id;
            $dup_room_price->save();
        } 

		//property step status duplicate
        $orgstep_status = PropertySteps::where('property_id',$property_id)->first();
        $dup_step_status = $orgstep_status->replicate();
        $dup_step_status->property_id = $duplicate_property->id;
        $dup_step_status->save();
		
		//property description duplicate
        $org_description = PropertyDescription::where('property_id',$property_id)->first();
        $dup_desc = $org_description->replicate();
        $dup_desc->property_id = $duplicate_property->id;
        $dup_desc->save();
		
		//property meta duplicate
        $org_meta = PropertyMeta::where('property_id',$property_id)->get();
        
        foreach($org_meta as $org_meta){
            $dup_meta = $org_meta->replicate();
            $dup_meta->property_id = $duplicate_property->id;
            $dup_meta->save();
        }
    	
		//property meta duplicate
        $org_dates_count = PropertyDates::where('property_id',$property_id)->count();
        if($org_dates_count!="0")
        {
            $org_dates = PropertyDates::where('property_id',$property_id)->first();

            $dup_dates = $org_dates->replicate();
            $dup_dates->property_id = $duplicate_property->id;
            $dup_dates->save();
        }

		//property photo duplicate
        $old_path = public_path().'/images/property/'.$property_id;
        $new_path = public_path().'/images/property/'.$duplicate_property->id;
        if (\File::isDirectory($old_path)) {
            \File::copyDirectory( $old_path, $new_path);
        }
        $original_photos = PropertyPhotos::where('property_id',$property_id)->get();
        foreach($original_photos as $original_property_photo){
            $duplicate_photo = $original_property_photo->replicate();
            $duplicate_photo->property_id = $duplicate_property->id;
            $duplicate_photo->save();
        }
        
       
        //property time duplicate(experience)
        $org_time_count = DB::table('property_time')->where('property_id',$property_id)->count();
       
        if(isset($org_time_count))
        {
            if($org_time_count!="0")
            {
                $original_time = DB::table('property_time')->where('property_id',$property_id)->get();
              
                     foreach($original_time as $original_time){
                        $duplicate_time = $original_time->replicate();
                        $duplicate_time->property_id = $duplicate_property->id;
                        $duplicate_time->save();
                    } 
                   
            }
        }

        //property time duplicate(experience)
        $org_package_count = FamilyPackage::where('property_id',$property_id)->count();
        if($org_package_count!="0")
        {
            $old_path = public_path().'/images/experience/'.$property_id;
            $new_path = public_path().'/images/experience/'.$duplicate_property->id;
            if (\File::isDirectory($old_path)) {
                \File::copyDirectory( $old_path, $new_path);
            }
        
            $original_package = FamilyPackage::where('property_id',$property_id)->get();
            foreach($original_package as $original_package){
                $duplicate_package = $original_package->replicate();
                $duplicate_package->property_id = $duplicate_property->id;
                $duplicate_package->save();
            }
        }
        return redirect()->back()->with('success', 'Duplicate Successfully');  
	} 

}
