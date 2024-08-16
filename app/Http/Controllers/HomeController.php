<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;

use App\Http\Helpers\Random;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Cache;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

use App\Http\{
    Controllers\EmailController
};


use View, Auth, App, Session, Route, DB, Mail;

use App\Models\{
    Currency,
    Amenities,
    Properties,
    Page,
    Settings,
    StartingCities,
    Testimonials,
    language,
    Admin,
    User,
    Wallet,
	SpaceType,
	Banners,
	PropertyType,
	Meta
};


require base_path() . '/vendor/autoload.php';

class HomeController extends Controller
{
    private $helper;
    
    public function __construct()
    {
        $this->helper = new Common;
      //  $this->helper1 = new Random;
    }

    public function index(Request $request)
    {
        $general = DB::table('settings')->where('type', 'general')->pluck('value', 'name')->toArray();
        
            $current_lang1 = Session::get('language');
            if($current_lang1=="")
            {
                $current_lang1 ="en";
            } 
            else
            {
                $current_lang1 = $current_lang1;
            }
    
            $data['starting_cities'] 	 = StartingCities::where('status', 'Active')->limit(8)->get();
            $data['properties']          = Properties::recommendedHome();
            $data['testimonials']        = Testimonials::getAll();
            $sessionLanguage             = Session::get('language');
            $language                    = Settings::where(['name' => 'default_language', 'type' => 'general'])->first();
    		
    		$data['recentproperties']    = Properties::where('status', '=', 'Listed')->where('type', 'property')->where('admin_approval', '=', '1')->where('deleted_status','No')->limit(8)->orderBy('id', 'DESC')->get();
            $languageDetails             = language::where(['id' => $language->value])->first();
            
        	$data['banner']              = Banners::where('status', '=', 'Active')->where('lang', $current_lang1)->orderBy('id', 'DESC')->get();
            $data['current_lang1']		 = Session::get('language');
    
    		$data['experience']    		      = Properties::where('status', '=', 'Listed')->where('type', 'experience')->where('admin_approval', '=', '1')->where('deleted_status','No')->limit(6)->orderBy('id', 'DESC')->get();
    		$data['recommended_experience']   = Properties::where('status', '=', 'Listed')->where('type', 'experience')->where('admin_approval', '=', '1')->where('deleted_status','No')->limit(8)->where('recomended', '1')->orderBy('id', 'DESC')->get();
            $data['property_type']            = PropertyType::where('status', 'Active')->where('lang', $current_lang1)->get();
            
            if (!($sessionLanguage)) {
                Session::pull('language');
                Session::put('language', $languageDetails->short_name);
                App::setLocale($languageDetails->short_name);
            }
    
            $pref = Settings::getAll();
            $prefer = [];
    
            if (!empty($pref)) {
                foreach ($pref as $value) {
                    $prefer[$value->name] = $value->value;
                }
                Session::put($prefer);
            }
    
           // $msg = $this->helper1->get_alllang();
    	//	if($msg == "success")
        	{
        	   // $msg1 = $this->helper1->get_otherlang();
        	} 
        	
            $metas                      = Meta::where('url', '/')->first();
            $data['title']              = $metas->title;

            $current_lang = Session::get('language');
            if($current_lang=="")
            {
                $current_lang = "en";
            }
            else
            {
                $current_lang = Session::get('language');
            }

            $data['property_type']      = PropertyType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');

            $google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
        $google_map_search_key = $google_map['google_map_search_key'];

        $location = $request->input('location');
        $address  = str_replace(" ", "+", "$location");
        $map_where = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address.'&sensor=false';
        
        $geocode  = $this->content_read($map_where);
        $json     = json_decode($geocode);

        if ($json->{'results'}) {
            $data['lat']  = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}:0;
            $data['long'] = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}:0;
        } else {
            $data['lat']  = 0;
            $data['long'] = 0;
        }

        $data['location']           = $request->input('location');
        $data['checkin']            = $request->input('checkin');
        $data['checkout']           = $request->input('checkout');
        $data['guest']              = $request->input('guest');
        $data['bedrooms']           = $request->input('bedrooms');
        $data['beds']               = $request->input('beds');
        $data['bathrooms']          = $request->input('bathrooms');
        $data['min_price']          = $request->input('min_price');
        $data['max_price']          = $request->input('max_price');

        $data['default_min_price'] = "1";
        $data['default_max_price'] = $this->helper->convert_currency('USD', '', 10000);

        if (!$data['min_price']) {
            $data['min_price'] = $data['default_min_price'];
            $data['max_price'] = $data['default_max_price'];
        }

        $data['space_type']         = SpaceType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');

        $data['space_type_selected'] = explode(',', $request->input('space_type'));
        $data['amenities']          = Amenities::where('status', 'Active')->where('lang', $current_lang)->get();
        $data['amenities_selected'] = explode(',', $request->input('amenities'));
            
            return view('home.home', $data); 
    }
    
    public function phpinfo()
    {
        echo phpinfo();
    }

    public function login()
    {
        return view('home.login');
    }

    public function setSession(Request $request)
    {
        if ($request->currency) {
            Session::put('currency', $request->currency);
            $symbol = Currency::code_to_symbol($request->currency);
            Session::put('symbol', $symbol);
        } elseif ($request->language) {
            Session::put('language', $request->language);
            $name = language::name($request->language);
            Session::put('language_name', $name);
            App::setLocale($request->language);
        }
    }

    public function cancellation_policies()
    {
        return view('home.cancellation_policies');
    }

    public function staticPages(Request $request)
    {
        /* $pages          = Page::where(['url'=>$request->name, 'status'=>'Active']);
        if (!$pages->count()) {
            abort('404');
        }
        $pages           = $pages->first();
        $data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url('/')], $pages->content);
        $data['title']   = $pages->url;
        $data['url']     = url('/').'/';
        $data['img']     = $data['url'].'public/images/2222hotel_room2.jpg';
        $data['testimonials']        = Testimonials::getAll(); */
		
		
		$current_lang = Session::get('language'); 
		if($current_lang=="")
		{
		    $current_lang ="en";
		}
		else
		{
		    $current_lang = $current_lang;
		}
        $pages        = Page::where(['url'=>$request->name, 'status'=>'Active']);
		
        if (!$pages->count()) {
            abort('404');
        }
        $pages           = $pages->first();
		$temp_id 		= $pages->temp_id;
		$pages1        = Page::where(['status'=>'Active', 'temp_id'=>$temp_id, 'lang'=>$current_lang ])->first();
		
        $data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url('/')], $pages1->content);
        $data['title']   = $pages->name;
        $data['url']     = url('/').'/';
        $data['img']     = $data['url'].'public/images/2222hotel_room2.jpg';
        $data['testimonials']        = Testimonials::getAll();
        return view('home.static_pages', $data);
    }


   

    public function walletUser(Request $request){

        $users = User::all();
        $wallet = Wallet::all();


        if (!$users->isEmpty() && $wallet->isEmpty() ) {
            foreach ($users as $key => $user) {

                Wallet::create([
                    'user_id' => $user->id,
                    'currency_id' => 1,
                    'balance' => 0,
                    'is_active' => 0
                ]);
            }
        }

        return redirect('/');

    }
    
    public function addContact(Request $request)
	{
	    $data1 		= $request->all();
		$username 	= $data1['username'];
		$useremail  = $data1['useremail'];
		$message1	= $data1['message'];
		
		DB::insert('insert into contact (username,useremail,message) values (?, ?, ?)', [$username,$useremail,$message1]);
		
        $data = [
          'username'    => $request->username,
          'useremail'   => $request->useremail,
          'message'     => $request->message
        ];
        
        $email_controller = new EmailController;
        $email_controller->contact_user($data);
		
		return back()->with('success', 'Your Message has been received');
	}
	
	public function svimport(Request $request)
	{	
	    if(config('global.demosite')=="yes")
		{
    		foreach(\DB::select('SHOW TABLES') as $table)
    		{
    			$table_array = get_object_vars($table);
    			\Schema::drop($table_array[key($table_array)]);
    		}
    		Artisan::call('cache:clear');
    		
    		$path = public_path('database.sql'); 
    
            $sql = file_get_contents($path); 
            DB::unprepared($sql);
    
    		$message = "Restore successfully completed";
    		return redirect('/')->with('isuccess', $message); 
		}
		else
		{
			return redirect('/');
		}
	}
	
	public function license(Request $request)
	{
	    $data = $request->txt;
	    $ROOT_URL = url('/');
	    echo '<h3 class="text-center">'.$data.'</h3><br>';
	    echo '<a class="btn btn-primary" href="'.$ROOT_URL.'">Go to Homepage</a>';
	    exit;
	}
	
	public function getproperty(Request $request)
	{
        $property_type_id = $request->id;
        /* $properties = Properties::where('type', 'property')
                       ->where('status', 'Listed')
                       ->where('admin_approval', 1)
                       ->where('deleted_status', 'No')
                       ->whereRaw('FIND_IN_SET("'.$property_type_id.'",property_type)')
                       ->get(); */
                       
     $properties = Properties::join('property_address', function ($join) {
                                    $join->on('properties.id', '=', 'property_address.property_id');
	    						})
	    						->join('property_price', function ($join) {
                                    $join->on('property_price.property_id', '=', 'properties.id');
                                 })
							    ->whereRaw('FIND_IN_SET("'.$property_type_id.'",properties.property_type)')
                                     ->where('properties.deleted_status', 'No')
                                      ->where('properties.type', 'property')
                                    ->where('properties.status', 'Listed')
                                    ->where('properties.admin_approval', '1')
                                    ->get();
                       
       echo $properties->toJson();

    }
    
    public function check_data(Request $request)
	{
	    $data = $request->txt;
	    $ROOT_URL = url('/');
	    echo '<h3 class="text-center">'.$data.'</h3><br>';
	    echo '<a class="btn btn-primary" href="'.$ROOT_URL.'">Go to Homepage</a>';
	    exit;
	}

    public function content_read($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result=curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
