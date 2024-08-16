<?php

namespace App\Http\Controllers;
use App\Http\Helpers\Common;
use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;
use Validator;

use App\Models\Properties;
use App\Models\Wishlist;
use App\Models\PropertyDetails;
use App\Models\PropertyAddress;
use App\Models\PropertyPhotos;
use App\Models\PropertyPrice;
use App\Models\PropertyType;
use App\Models\PropertyDates;
use App\Models\PropertyDescription;
use App\Models\Currency;
use App\Models\SpaceType;
use App\Models\BedType;
use App\Models\PropertySteps;
use App\Models\Country;
use App\Models\Amenities;
use App\Models\AmenityType;
use DB;

class WishlistController extends Controller
{
    public function __construct(){
        $this->helper = new Common;
    }

    public function index(){
        $data['title']          = 'My Wishlist';
        $data['listed']   		= Properties::orderBy('properties.id', 'desc')->get();
        $data['userdata'] 		= Wishlist::where('userid', \Auth::user()->id)->where('status','1')->orderBy('id', 'desc')->get();
        $data['unlisted'] 		= Wishlist::where('userid', \Auth::user()->id)->where('status','1')->get();

        return view('wishlist.listings', $data);
    }
    
    
    public function get_price(Request $request){
		
       return $this->helper->get_price($request->property_id, $request->checkin, $request->checkout, $request->guest_count);
    }


    public function currency_symbol(Request $request){
        $symbol          = Currency::code_to_symbol($request->currency);
        $data['success'] = 1;
        $data['symbol']  = $symbol;

        return json_encode($data);
    }

  
    public function wishlist(Request $request){
        $propertyid = $request->wishid;
        DB::table('wishlist')->where('propertyid', $propertyid)->delete();
        if($propertyid!='') { DB::table('wishlist')->insert(array( 'propertyid' => $propertyid,'userid' => \Auth::user()->id ,'status' => '1'    )
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


}
