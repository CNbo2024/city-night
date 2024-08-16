<?php
namespace App\Http\Controllers;

use Auth, DB, validator;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use App\Http\Helpers\Random;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\{
    PropertyPhotos,
    Messages,
    Properties,
    User,
    Bookings,
    BookingDetails
};


class InboxController extends Controller
{
    private $helper;
    
    public function __construct()
    {
        $this->helper = new Common;
      //  $this->inbox  = new Random;
    }
    
    /**
    * Inbox Page
    * Conversassion List
    * Message View
    */
    public function index(Request $request)
    {
        $bookings = Bookings::where('host_id', auth()->user()->id)
            ->orWhere('user_id', auth()->user()->id)
            ->orWhere(function ($query) {
                $email = auth()->user()->email;
                $query->whereIn('host_id', User::where('cohosts', 'LIKE', '%' . $email . '%')->get()->pluck('id'));
            })
            ->get()
            ->pluck('id');

        $data['messages']  = Messages::whereIn('booking_id', $bookings)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('booking_id');

        if ( count($data['messages']) > 0 ) {
            $booking_id             = $data['messages'][0]->booking_id;
            $data['conversassion']  = Messages::where('booking_id', $booking_id)->get();

            if ($request->support) {
                $data['conversassion']  = Messages::where('booking_id', 0)->get();
            }

            $data['booking']        = Bookings::where('id', $booking_id)
                                                ->with('users','properties')
                                                ->first();
            $data['booking_info']    = BookingDetails::where('booking_id', $booking_id)->where('field', 'price')->first();
            
            $data['booking_packages'] = DB::table('booking_packages')->where('booking_id', $booking_id)->get();

            $data['images'] = PropertyPhotos::where('property_id', $data['booking']->property_id)->where('type','photo')->orderBy('serial', 'asc')->get();

        }

        $data['request'] = $request;

        if ($request->user_id) {
            $data['userInfo'] = User::find($request->user_id);
        }


       // $txt = $this->inbox->allmsg();
	//	if($txt == "success")
    	{
    	  //  $txt = $this->inbox->readmsg();
    	}
        return view('users.inbox', $data); 
    }

    /**
    * Message Read status Change
    * Details pass according to booking message
    */
    public function message(Request $request)
    {
        $booking_id = $request->id;
        $message = Messages::where([['booking_id', '=', $booking_id], ['receiver_id', '=', Auth::id()]])->update(['read' => 1]);
 
        $data['messages'] = Messages::where('booking_id', $booking_id)->get();
        $data['booking'] = Bookings::where('id', $booking_id)
                          ->with('host')->first();
                          
        $data['booking_info']    = BookingDetails::where('booking_id', $booking_id)->where('field', 'price')->first();
        $data['booking_packages'] = DB::table('booking_packages')->where('booking_id', $booking_id)->get();
        $data['images'] = PropertyPhotos::where('property_id', $data['booking']->property_id)->where('type','photo')->orderBy('serial', 'asc')->get();
                  
        return response()->json([
             "inbox"=>view('users.messages', $data)->render(), "booking"=>view('users.booking', $data)->render()
        ]);
    }

    /**
    * Message Reply 
    * Message read status change
    */
    public function messageReply(Request $request)
    {
        $messages = Messages::where([['booking_id', '=', $request->booking_id], ['receiver_id', '=', Auth::id()]])->update(['read' => 1]);

        $rules = array(
            'msg'      => 'required|string',
        );

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {     
            $message = new Messages;
            $message->property_id = $request->property_id;
            $message->booking_id = $request->booking_id;
            $message->receiver_id = $request->receiver_id;
            $message->sender_id = Auth::id();
            $message->message = $request->msg;
            $message->type_id = 1;
            $message->save();
            return 1;
        }
    }
}
