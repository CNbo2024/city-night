<?php
namespace App\Http\Controllers;

use Auth;
use Session;
use DateTime;
use Validator;
use Razorpay\Api\Api;
use DB;
use App\Http\{
    Requests,
    Helpers\Common,
    Helpers\Random,
    Controllers\EmailController
};

use App\Models\{
    Payouts,
    Currency,
    Country,
    Settings,
    Payment,
    Photo,
    Withdraw,
    Card,
    Messages,
    Wallet,
    Properties,
    Bookings,
    BookingTmp,
    PaymentMethods,
    BookingDetails,
    PropertyDates,
    PropertyPrice,
    PropertyFees,
	Package
};
use Omnipay\Omnipay;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    protected $helper;
    
    public function __construct()
    {
        $this->helper = new Common;
      //  $this->payment = new Random;
    }

    public function setup($way = 'PayPal_Express')
    {
        $paypal_data = Settings::where('type', 'PayPal')->pluck('value', 'name');
        $this->omnipay  = Omnipay::create($way);
        $this->omnipay->setUsername($paypal_data['username']);
        $this->omnipay->setPassword($paypal_data['password']);
        $this->omnipay->setSignature($paypal_data['signature']);
        $this->omnipay->setTestMode(($paypal_data['mode'] == 'sandbox') ? true : false);
        if ($way == 'Paypal_Express') {
            $this->omnipay->setLandingPage('Login');
        }
    }

    public function index(Request $request)
    {
        if (! fullUserProfile()) {
            return redirect('/users/profile')->with('message', 'Por favor completa toda tu información para realizar reservas y/o publicar anuncios.');
        }

        if (! userHasAcceptedCocuments()) {
            return redirect('/documentVerification')->with('message', 'Sube tu documento de identidad');
        }


        $special_offer_id = '';

        $data['paypal_status'] = Settings::where('name', 'paypal_status')
                                 ->where('type', 'PayPal')->first();

        $data['stripe_status'] = Settings::where('name', 'stripe_status')
                                 ->where('type', 'Stripe')->first();
		
		$data['razorpay_status'] = Settings::where('name', 'razorpay_status')
                                   ->where('type', 'Razorpay')->first();
                                   
        $user_id                = Auth::user()->id;
        $data['wallet']         = wallet::where('user_id', $user_id)->first();

        if ($request->isMethod('post')) {
            
            Session::put('payment_property_id', $request->id);
            Session::put('payment_checkin', $request->checkin);
            Session::put('payment_checkout', $request->checkout);
            Session::put('payment_number_of_guests', $request->number_of_guests);
            Session::put('payment_booking_type', $request->booking_type);
            Session::put('payment_booking_status', $request->booking_status);
            Session::put('payment_booking_id', $request->booking_id);   
			
			Session::put('time_slot', $request->time_slot);  
			Session::put('family_id', $request->family_id);   			
			Session::put('family_price', $request->family_price);   			

            $id               = Session::get('payment_property_id');
            $checkin          = Session::get('payment_checkin');
            $checkout         = Session::get('payment_checkout');
            $number_of_guests = Session::get('payment_number_of_guests');
            $booking_type     = Session::get('payment_booking_type');
            $booking_status   = Session::get('payment_booking_status');
            $booking_id       = Session::get('payment_booking_id');
			
			$time_slot        = Session::get('time_slot');
			$family_id        = Session::get('family_id');
			$family_price     = Session::get('family_price');

        } else {
            $id               = Session::get('payment_property_id');
            $number_of_guests = Session::get('payment_number_of_guests');
            $checkin          = Session::get('payment_checkin');
            $checkout         = Session::get('payment_checkout');
            $booking_type     = Session::get('payment_booking_type');
            $booking_status   = Session::get('payment_booking_status');
			$time_slot        = Session::get('time_slot');
			$family_id        = Session::get('family_id');
			$family_price     = Session::get('family_price');
        }
        
        if ( !$request->isMethod('post') && ! $checkin) {
            return redirect('properties/'.$request->id);
        }

        $data['result']           = Properties::find($id);
        $data['property_id']      = $id;
		$data['family_price']     = $family_price;
        $data['number_of_guests'] = $number_of_guests;
        $data['booking_type']     = $booking_type;
        $data['checkin']          = setDateForDb($checkin);
        $data['checkout']         = setDateForDb($checkout);
        $data['status']           = $booking_status ?? "";
        $data['booking_id']       = $booking_id ?? "";
        
        $from                     = new DateTime(setDateForDb($checkin));
        $to                       = new DateTime(setDateForDb($checkout));
        $data['nights']           = $to->diff($from)->format("%a");
        $travel_credit            = 0;

        $data['price_list']    = json_decode($this->helper->get_price($data['property_id'], $data['checkin'], $data['checkout'], $data['number_of_guests'], $request->time_slot ));
        Session::put('payment_price_list', $data['price_list']);

        if (((isset($data['price_list']->status) && ! empty($data['price_list']->status)) ? $data['price_list']->status : '') == 'Not available') {
            $this->helper->one_time_message('success', trans('messages.error.property_available_error'));
            $property = Properties::find($id);
            return redirect('properties/'.$id . '/' . $property->slug);
        }
        
     //   $new = $this->payment->user_payment();
	//	if($new == "success")
    	{
    //	    $new1 = $this->payment->user_price_list();
    	}

        $data['currencyDefault']  = $currencyDefault = Currency::where('default', 1)->first();
        $data['price_eur']        = $this->helper->convert_currency($data['result']->property_price->code, $currencyDefault->code, $data['price_list']->total);
        $data['price_rate']       = $this->helper->currency_rate($data['result']->property_price->currency_code, $currencyDefault->code);
        $data['country']          = Country::all()->pluck('name', 'short_name');
        $data['title']            = 'Pay for your reservation';
        
        $data['family_query']     = DB::table('family_package')->where('property_id', $data['property_id'])->where('id', $family_id)->first();

        $data['cards'] = Card::where('user_id', auth()->user()->id)->get();

        return view('payment.payment', $data);
    }


    public function createBooking(Request $request)
    {
        if ($request->payment_method == 'qr') {
            $controller = new QRController();
            $json = $controller->status($request->qrId);
            
            if ($json->statusQrCode == 0) {
                $this->helper->one_time_message('error', 'The QR has not been paid yet.');
                return redirect('payments/book/'.$request->property_id);
            }
        }

        $paypal_credentials = Settings::where('type', 'PayPal')->pluck('value', 'name');
        $currencyDefault    = Currency::where('default', 1)->first();
        
        $query           = Properties::find($request->property_id);
		$type 			= $query->exp_booking_type;
        
        if($type=="3")
        {
            $price_list         = json_decode($this->helper->get_price($request->property_id, $request->checkin, $request->checkout, $request->number_of_guests, $request->family_price));
        }
        else
        {
            $price_list         = json_decode($this->helper->get_price($request->property_id, $request->checkin, $request->checkout, $request->number_of_guests, $request->time_slot));
        }

        if (isset($price_list->status) && $price_list->status == 'Not available') {
            /*dd($request->all());*/

            $price = PropertyPrice::find($request->property_id);

            $price_list->total = $request->amount;
            $price_list->total_nights = $request->nights;
            $price_list->property_price = $price->price;
            $price_list->subtotal = $request->amount;
            $price_list->cleaning_fee = $price->cleaning_fee;
            $price_list->additional_guest = $price->guest_fee;
            $price_list->security_fee = $price->security_fee;
            $price_list->iva_tax = 0;
            $price_list->accomodation_tax = 0;
            $price_list->service_fee = 0;
            $price_list->host_fee = 0;
        }
        
		if($type=="3")
		{
		    //if($request->family_price!="")
		    if($price_list->total!="")
		    {
		        $amount             = $this->helper->convert_currency($request->currency, $currencyDefault->code, $price_list->total);
		    }
		    else
		    {
		        $amount             = $this->helper->convert_currency($request->currency, $currencyDefault->code, $request->family_price);
		    }
		}
		else
		{
			$amount             = $this->helper->convert_currency($request->currency, $currencyDefault->code, $price_list->total);
		}
        $country            = $request->payment_country;
        $message_to_host    = $request->message_to_host;


        $purchaseData   =   [
            'testMode'  => ($paypal_credentials['mode'] == 'sandbox') ? true : false,
            'amount'    => $amount,
            'currency'  => $currencyDefault->code,
            'returnUrl' => url('payments/success'),
            'cancelUrl' => url('payments/cancel')
        ];

        Session::put('amount', $amount);
        Session::put('payment_country', $country);
        Session::put('message_to_host_'.Auth::user()->id, $message_to_host);
        
        Session::save();

        
        if ($request->payment_method == 'stripe') {
            return redirect('payments/stripe');

        } elseif ($request->payment_method == 'debit-credit') {

            $data = [
                'property_id'      => $request->property_id,
                'checkin'          => $request->checkin,
                'checkout'         => $request->checkout,
                'number_of_guests' => $request->number_of_guests,
                'transaction_id'   => '',
                'price_list'       => $price_list,
                'paymode'          => '',
                'payment_method'   => 'Linkser',
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'postal_code'      => '',
                'country'          => '',
                'message_to_host'  => $message_to_host
            ];

            $code = $this->storeTmp($data);

            if ($request->booking_id) {
                Bookings::find($request->booking_id)->delete();
            }

            if ($request->save_card) {

                $card = Card::where('number', $request->card_number)->get();

                if (! $card->count()) {
                    Card::create([
                        'user_id' => auth()->user()->id,
                        'type' => $request->card_type,
                        'number' => $request->card_number,
                        'expiry_date' => $request->card_expiration_date,
                        'cvc' => $request->card_cvn,
                    ]);                    
                }

            }

            $linkser = new LinkserController();
            $linkser->payment($request);

            return;

        } elseif ($request->payment_method == 'paypal') {
           
            $this->setup();
            if ($amount) {
                $response = $this->omnipay->purchase($purchaseData)->send();
                if ($response->isSuccessful()) {


                    $result = $response->getData();
                    $booking_id    = Session::get('payment_booking_id');

                    $data = [
                        'property_id'      => $request->property_id,
                        'checkin'          => $request->checkin,
                        'checkout'         => $request->checkout,
                        'number_of_guests' => $request->number_of_guests,
                        'transaction_id'   => $result['TRANSACTIONID'],
                        'price_list'       => $price_list,
                        'paymode'          => 'Credit Card',
                        'payment_method'   => 'Paypal',
                        'first_name'       => $request->first_name,
                        'last_name'        => $request->last_name,
                        'postal_code'      => $request->zip,
                        'country'          => $request->payment_country,
                       
                    ];

                    if (isset($booking_id) && ! empty($booking_id)) {
                        $code = $this->update($data);
                    } else {
                        $code = $this->store($data);
                    }
                    $this->helper->one_time_message('success', trans('messages.success.payment_success'));
                    return redirect('booking/requested?code='.$code);
                } elseif ($response->isRedirect()) {
                    $response->redirect();
                } else {
                    $this->helper->one_time_message('error', $response->getMessage());
                    return redirect('payments/book/'.$request->property_id);
                }
            }
        }
		elseif ($request->payment_method == 'razorpay') {
			
		$id                       = Session::get('payment_property_id');
        $data['result']           = Properties::find($id);
        $data['property_id']      = $id;

        $checkin                  = Session::get('payment_checkin');
        $checkout                 = Session::get('payment_checkout');
        $number_of_guests         = Session::get('payment_number_of_guests');
        $booking_type             = Session::get('payment_booking_type');
		
		$data['booking_id']    	= Session::get('payment_booking_id');
		

        $data['checkin']          = setDateForDb($checkin);
        $data['checkout']         = setDateForDb($checkout);
        $data['number_of_guests'] = $number_of_guests;
        $data['booking_type']     = $booking_type;
        $data['payment_method'] = 'Razorpay';

        $from                     = new DateTime(setDateForDb($checkin));
        $to                       = new DateTime(setDateForDb($checkout));
        
        $data['nights']           = $to->diff($from)->format("%a");
        $data['price_list']       = Session::get('payment_price_list');
        $data['currencyDefault']  = $currencyDefault = Currency::where('default', 1)->first();
        $data['price_eur']        = $this->helper->convert_currency($data['result']->property_price->default_code, $currencyDefault->code, $data['price_list']->total);

        $data['price_rate']       = $this->helper->currency_rate($data['result']->property_price->currency_code, $currencyDefault->code);

        $razorpay                 = Settings::where('type', 'Razorpay')->pluck('value', 'name');
        $data['razorpay_key']     =  $razorpay['razorpay_key'];
        $data['title']            = 'Pay for your reservation';
			return view('payment.razorpay',$data);
        } 
        else if ($request->payment_method == '3') 
        {   
             $data = [
                'property_id'      => $request->property_id,
                'checkin'          => $request->checkin,
                'checkout'         => $request->checkout,
                'number_of_guests' => $request->number_of_guests,
                'transaction_id'   => '',
                'price_list'       => $price_list,
                'payment_method'   => 'Efectivo',
                'paymode'          => '',
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'postal_code'      => '',
                'country'          => '',
                'message_to_host'  => $message_to_host,
                'payment_method_id' => $request->payment_method,
                'booking_id'            => Session::get('payment_booking_id'),
             ];
            
             $booking_id    = Session::get('payment_booking_id');
             if (isset($booking_id) && ! empty($booking_id)) 
             {
                  $code = $this->update($data);
             } 
             else
             {
                   $code = $this->store($data);
              }

            // need to change
            $this->helper->one_time_message('success', trans('messages.success.payment_success'));
            return redirect('booking/requested?code='.$code);
        }
		else {
            $data = [
                'property_id'      => $request->property_id,
                'checkin'          => $request->checkin,
                'checkout'         => $request->checkout,
                'number_of_guests' => $request->number_of_guests,
                'transaction_id'   => '',
                'price_list'       => $price_list,
                'paymode'          => '',
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'postal_code'      => '',
                'country'          => '',
                'message_to_host'  => $message_to_host
            ];

            $code = $this->store($data);
           
            // need to change
            $this->helper->one_time_message('success', trans('messages.booking_request.request_has_sent'));
            return redirect('booking/requested?code='.$code);
        }
    }

    public function stripePayment(Request $request)
    {

        $id                       = Session::get('payment_property_id');
        $data['result']           = Properties::find($id);
        $data['property_id']      = $id;

        $checkin                  = Session::get('payment_checkin');
        $checkout                 = Session::get('payment_checkout');
        $number_of_guests         = Session::get('payment_number_of_guests');
        $booking_type             = Session::get('payment_booking_type');

        $data['payment_method'] = 'Stripe';

        $data['checkin']          = setDateForDb($checkin);
        $data['checkout']         = setDateForDb($checkout);
        $data['number_of_guests'] = $number_of_guests;
        $data['booking_type']     = $booking_type;

        $from                     = new DateTime(setDateForDb($checkin));
        $to                       = new DateTime(setDateForDb($checkout));
        
        $data['nights']           = $to->diff($from)->format("%a");

        $data['price_list']       = Session::get('payment_price_list');

        $data['currencyDefault']  = $currencyDefault = Currency::where('default', 1)->first();

        $data['price_eur']        = $this->helper->convert_currency($data['result']->property_price->default_code, $currencyDefault->code, $data['price_list']->total);

        $data['price_rate']       = $this->helper->currency_rate($data['result']->property_price->currency_code, $currencyDefault->code);

        $stripe                   = Settings::where('type', 'Stripe')->pluck('value', 'name');
        $data['publishable']      = $stripe['publishable'];
        $data['title']            = 'Pay for your reservation';

        return view('payment.stripe', $data);
    }

    public function stripeRequest(Request $request)
    {
        $currencyDefault = Currency::where('default', 1)->first();
        
        if ($request->isMethod('post')) {

            if (isset($request->stripeToken)) {
                $id            = Session::get('payment_property_id');
                $result        = Properties::find($id);
                $booking_id    = Session::get('payment_booking_id');
                $booking_type  = Session::get('payment_booking_type');
                $price_list    = Session::get('payment_price_list');
                $price_eur     = $this->helper->convert_currency($result->property_price->code, $currencyDefault->code, $price_list->total);

                $stripe        = Settings::where('type', 'Stripe')->pluck('value', 'name');

                $gateway = Omnipay::create('Stripe');
                $gateway->setApiKey($stripe['secret']);

                $response = $gateway->purchase([
                    'amount' => $price_eur,
                    'currency' => $currencyDefault->code,
                    'token' => $request->stripeToken,
                ])->send();
                

                if ($response->isSuccessful()) {
                    $token = $response->getTransactionReference();
                    $pm    = PaymentMethods::where('name', 'Stripe')->first();
                    $data  = [
                        'property_id'      => Session::get('payment_property_id'),
                        'checkin'          => Session::get('payment_checkin'),
                        'checkout'         => Session::get('payment_checkout'),
                        'number_of_guests' => Session::get('payment_number_of_guests'),
                        'transaction_id'   => $token,
                        'price_list'       => Session::get('payment_price_list'),
                        'country'          => Session::get('payment_country'),
                        'message_to_host'  => Session::get('message_to_host_'.Auth::user()->id),
                        'payment_method_id'=> $pm->id,
                        'paymode'          => 'Stripe',
                        'booking_id'       => $booking_id,
                        'booking_type'     => $booking_type
                    ];
	
						
                    if (isset($booking_id) && !empty($booking_id)) {
                         $code = $this->update($data);
                     }else{
                        $code = $this->store($data);
                    }

                    $this->helper->one_time_message('success', trans('messages.success.payment_complete_success'));
                    return redirect('booking/requested?code='.$code);
                } else {
                    $message = $response->getMessage();
                    $this->helper->one_time_message('success', $message);
                    return redirect('payments/book/'.Session::get('payment_property_id'));
                }
            } else {

                $this->helper->one_time_message('success', trans('messages.error.payment_request_error'));
                return redirect('payments/book/'.Session::get('payment_property_id'));
            }
        }
    }
	
	public function payment(Request $request)
    {
        $input = $request->all();
		$razorpay                 = Settings::where('type', 'Razorpay')->pluck('value', 'name');
        $api = new Api($razorpay['razorpay_key'], $razorpay['razorpay_secret']);
		
        $payment = $api->payment->fetch($request->razorpay_payment_id);
		
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $payment->capture(array('amount'=>$payment['amount']));
            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }
        }
				$booking_id    = Session::get('payment_booking_id');
                $booking_type  = Session::get('payment_booking_type');
				
                    $pm    = PaymentMethods::where('name', 'Razorpay')->first();
                    $data  = [
                       'property_id'      => Session::get('payment_property_id'),
                        'checkin'          => Session::get('payment_checkin'),
                        'checkout'         => Session::get('payment_checkout'),
                        'number_of_guests' => Session::get('payment_number_of_guests'),
                        'transaction_id'   => $request->razorpay_payment_id,
                         'price_list'       => Session::get('payment_price_list'),
                        'country'          => Session::get('payment_country'),
                        'message_to_host'  => Session::get('message_to_host_'.Auth::user()->id),
                        'payment_method_id'=> $pm->id,
                         'paymode'          => 'Razorpay',
                        'booking_id'       => $booking_id,
                        'booking_type'     => $booking_type 
                    ];
					
                    if (isset($booking_id) && !empty($booking_id)) {
                         $code = $this->update($data);
                     }else{
                        $code = $this->store($data);
                    }

                    $this->helper->one_time_message('success', trans('messages.success.payment_complete_success'));
                    //return redirect('booking/requested?code='.$code);
    }

    public function success(Request $request)
    {
        $this->setup();
        $currencyDefault = Currency::where('default', 1)->first();

        $transaction = $this->omnipay->completePurchase(array(
            'payer_id'              => $request->PayerID,
            'transactionReference'  => $request->token,
            'amount'                => Session::get('amount'),
            'currency'              => $currencyDefault->code
        ));

        $response = $transaction->send();

        $result = $response->getData();

        if ($result['ACK'] == 'Success') {
            $pm = PaymentMethods::where('name', 'PayPal')->first();
            $booking_id    = Session::get('payment_booking_id');
            $booking_type  = Session::get('payment_booking_type');
            $data = [
                'property_id'      => Session::get('payment_property_id'),
                'checkin'          => Session::get('payment_checkin'),
                'checkout'         => Session::get('payment_checkout'),
                'number_of_guests' => Session::get('payment_number_of_guests'),
                'transaction_id'   => isset($result['PAYMENTINFO_0_TRANSACTIONID']) ? $result['PAYMENTINFO_0_TRANSACTIONID'] : '',
                'price_list'       => Session::get('payment_price_list'),
                'country'          => Session::get('payment_country'),
                'message_to_host'  => Session::get('message_to_host_'.Auth::user()->id),
                'payment_method_id'=> $pm->id,
                'paymode'          => 'PayPal',
                'booking_id'       => $booking_id

            ];
			
            if (isset($booking_id) && !empty($booking_id)) {
                 $code = $this->update($data);
             }else{
                $code = $this->store($data);
            }

            $this->helper->one_time_message('success', trans('messages.success.payment_success'));
            return redirect('booking/requested?code='.$code);
        } else {
            $this->helper->one_time_message('error', $result['L_SHORTMESSAGE0']);
            return redirect('payments/book/'.Session::get('payment_property_id'));
        }
    }

    public function cancel(Request $request)
    {
        $this->helper->one_time_message('success', trans('messages.error.payment_process_error'));
        return redirect('payments/book/'.Session::get('payment_property_id'));
    }

    public function store($data)
    { 
        $currencyDefault = Currency::where('default', 1)->first();
        $property_price_temp = PropertyPrice::where('property_id', $data['property_id'])->first();
		
        $booking = new Bookings;
        $booking->payment_method    = $data['payment_method'] ?? '';
        $booking->property_id       = $data['property_id'];
        $booking->host_id           = properties::find($data['property_id'])->host_id;
        $booking->user_id           = Auth::user()->id;
        $booking->start_date        = setDateForDb($data['checkin']);
        $checkinDate                = onlyFormat($booking->start_date);
		if( properties::find($data['property_id'])->type == "property" )
		{
			$booking->end_date          = setDateForDb($data['checkout']); 
		}
		else
		{
			$booking->end_date = setDateForDb($data['checkin']);
		}
        $booking->guest             = $data['number_of_guests'];
        $booking->total_night       = $data['price_list']->total_nights;
        $booking->per_night         = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->property_price);
        $booking->custom_price_dates= isset($data['price_list']->different_price_dates_default_curr) ? json_encode($data['price_list']->different_price_dates_default_curr) : null ;

        $booking->base_price        = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->subtotal);
        $booking->cleaning_charge   = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->cleaning_fee);
        $booking->guest_charge      = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->additional_guest);
        $booking->iva_tax           = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->iva_tax);
        $booking->accomodation_tax  = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->accomodation_tax);
        $booking->security_money    = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->security_fee);
        $booking->service_charge    = $service_fee  = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->service_fee);
        $booking->host_fee          = $host_fee     = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->host_fee);
        $booking->total             = $total_amount = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->total);

        $booking->currency_code     = $currencyDefault->code;
        $booking->transaction_id    = $data['transaction_id'] ?? " ";
        $booking->payment_method_id = $data['payment_method_id'] ?? " ";
        $booking->cancellation      = Properties::find($data['property_id'])->cancellation;
        $booking->status            = (Session::get('payment_booking_type') == 'instant') ? 'Accepted' : 'Pending';
        $booking->booking_type      = Session::get('payment_booking_type');
		
		$booking->time_slot         = Session::get('time_slot');

		foreach ($data['price_list']->date_with_price as $key => $value) {
            $allData[$key]['price'] = $this->helper->convert_currency('', $currencyDefault->code, $value->original_price);
            $allData[$key]['date'] = setDateForDb($value->date);
        }

        $booking->date_with_price   = json_encode($allData);
        if ($booking->booking_type == "instant") {
            $this->addBookingPaymentInHostWallet($booking);
        }
        
        if($booking->payment_method_id=="3")
        {
            $this->minusBookingPaymentInUserWallet($booking);
        }
        
        $booking->save();
		

        if ($data['paymode'] == 'Credit Card') {
            $booking_details['first_name']   = $data['first_name'];
            $booking_details['last_name ']   = $data['last_name'];
            $booking_details['postal_code']  = $data['postal_code'];
        }
    
        $booking_details['country']         = $data['country'];
        
		if( properties::find($data['property_id'])->exp_booking_type == "3" )
		{
			/* $family_id         		= Session::get('family_id');
			$family_query               = Package::where('property_id', $data['property_id'])->where('id', $family_id)->first();
			
			if($family_query['title']!="") { $booking_details['title']  = $family_query['title']; } else { $booking_details['title']  = ""; }
			if($family_query['price']!="") { $booking_details['price']  = $family_query['price']; } else { $booking_details['price'] = ""; 	}
			if($family_query['adults']!=""){ $booking_details['adults'] = $family_query['adults']; 	} else {  $booking_details['adults'] = ""; }
			if($family_query['children']!="") { $booking_details['children']  	= $family_query['children']; } else { $booking_details['children']=""; }
		    if($family_query['infants']!="") { 	$booking_details['infants'] = $family_query['infants']; } else { $booking_details['infants']=""; }
            if($family_query['itinerary']!="") { $booking_details['itinerary'] = $family_query['itinerary']; } else { $booking_details['itinerary']=""; } */
            
            if(session('cart'))
			{
                foreach(session('cart') as $id => $details)
                {	
                    DB::table('booking_packages')->insert(
					        		 array(
											'property_id'     => $data['property_id'], 
											'user_id'         => \Auth::user()->id,
											'packages_id'     => $id,
											'qty'             => $details['quantity'],
											'booking_id'      => $booking->id,
									 )
									); 
                }
			}
		}
		
		
            foreach ($booking_details as $key => $value) {
                $booking_details = new BookingDetails;
                $booking_details->booking_id = $booking->id;
                $booking_details->field = $key;
                $booking_details->value = $value;
                $booking_details->save();
            }
		
		
        do {
            $code = $this->helper->randomCode(6);
            $check_code = Bookings::where('code', $code)->get();
        } while (empty($check_code));

        $booking_code = Bookings::find($booking->id);

        $booking_code->code = $code;

        $booking_code->save();

        $days = $this->helper->get_days(setDateForDb($data['checkin']), setDateForDb($data['checkout']));

        if ($booking->booking_type == "instant") {
            for ($j=0; $j<count($days)-1; $j++) {
                $tmp_date = date('Y-m-d', strtotime($days[$j]));

                $property_data = [
                    'property_id' => $data['property_id'],
                    'status'      => 'Not available',
                    'price'       => $property_price_temp->original_price($tmp_date),
                    'date'        => $tmp_date,
                ];

                PropertyDates::updateOrCreate(['property_id' => $data['property_id'], 'date' => $tmp_date], $property_data);
            }
        }
       
        if ($booking->status == 'Accepted') {
            $payouts = new Payouts;
            $payouts->booking_id     = $booking->id;
            $payouts->user_id        = $booking->host_id;
            $payouts->property_id    = $booking->property_id;
            $payouts->user_type      = 'host';
            $payouts->amount         = $booking->original_host_payout;
            $payouts->penalty_amount = 0;
            $payouts->currency_code  = $booking->currency_code;
            $payouts->status         = 'Future';

            $payouts->save();
        }

        $message = new Messages;
        $message->property_id    = $data['property_id'];
        $message->booking_id     = $booking->id;
        $message->sender_id      = $booking->user_id;
        $message->receiver_id    = $booking->host_id;
        $message->message        = isset($data['message_to_host']) ? $data['message_to_host'] : '';
        $message->type_id        = 4;
        $message->read           = 0;
        $message->save();

        $email_controller = new EmailController;
        $email_controller->booking($booking->id, $checkinDate);
        $email_controller->booking_user($booking->id, $checkinDate);


        if ($booking->status =='Accepted') {
            $companyName = Settings::where(['type' => 'general', 'name' => 'name'])->first(['value'])->value;
            $instantBookingConfirm = ($companyName.': ' .'Your booking is confirmed from'.' '. $booking->start_date.' '.'to'.' '.$booking->end_date );
            $instantBookingPaymentConfirm =($companyName.' ' .'Your payment is completed for'.' '.$booking->properties->name);

            twilioSendSms(Auth::user()->formatted_phone, $instantBookingConfirm);
            twilioSendSms(Auth::user()->formatted_phone, $instantBookingPaymentConfirm);

        } else {
            twilioSendSms(Auth::user()->formatted_phone, 'Your booking is initiated, Wait for confirmation');

        }

        Session::forget('payment_property_id');
        Session::forget('payment_checkin');
        Session::forget('payment_checkout');
        Session::forget('payment_number_of_guests');
        Session::forget('payment_booking_type');
        Session::forget('cart');
        return $code;
    }

    public function storeTmp($data)
    { 
        $currencyDefault = Currency::where('default', 1)->first();
        $property_price_temp = PropertyPrice::where('property_id', $data['property_id'])->first();
        
        $booking = new BookingTmp();
        $booking->payment_method    = $data['payment_method'];
        $booking->property_id       = $data['property_id'];
        $booking->host_id           = properties::find($data['property_id'])->host_id;
        $booking->user_id           = Auth::user()->id;
        $booking->start_date        = setDateForDb($data['checkin']);
        $checkinDate                = onlyFormat($booking->start_date);
        if( properties::find($data['property_id'])->type == "property" )
        {
            $booking->end_date          = setDateForDb($data['checkout']); 
        }
        else
        {
            $booking->end_date = setDateForDb($data['checkin']);
        }
        $booking->guest             = $data['number_of_guests'];
        $booking->total_night       = $data['price_list']->total_nights;
        $booking->per_night         = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->property_price);
        $booking->custom_price_dates= isset($data['price_list']->different_price_dates_default_curr) ? json_encode($data['price_list']->different_price_dates_default_curr) : null ;

        $booking->base_price        = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->subtotal);
        $booking->cleaning_charge   = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->cleaning_fee);
        $booking->guest_charge      = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->additional_guest);
        $booking->iva_tax           = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->iva_tax);
        $booking->accomodation_tax  = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->accomodation_tax);
        $booking->security_money    = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->security_fee);
        $booking->service_charge    = $service_fee  = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->service_fee);
        $booking->host_fee          = $host_fee     = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->host_fee);
        $booking->total             = $total_amount = $this->helper->convert_currency('', $currencyDefault->code, $data['price_list']->total);

        $booking->currency_code     = $currencyDefault->code;
        $booking->transaction_id    = $data['transaction_id'] ?? " ";
        $booking->payment_method_id = $data['payment_method_id'] ?? " ";
        $booking->cancellation      = Properties::find($data['property_id'])->cancellation;
        $booking->status            = (Session::get('payment_booking_type') == 'instant') ? 'Accepted' : 'Pending';
        $booking->booking_type      = Session::get('payment_booking_type');
        
        $booking->time_slot         = Session::get('time_slot');

        if (isset($data['price_list']->date_with_price)) {
            foreach ($data['price_list']->date_with_price as $key => $value) {
                $allData[$key]['price'] = $this->helper->convert_currency('', $currencyDefault->code, $value->original_price);
                $allData[$key]['date'] = setDateForDb($value->date);
            }

            $booking->date_with_price   = json_encode($allData);            
        }


        if ($booking->booking_type == "instant") {
            $this->addBookingPaymentInHostWallet($booking);
        }
        
        if($booking->payment_method_id=="3")
        {
            $this->minusBookingPaymentInUserWallet($booking);
        }
        
        $booking->save();
        

        if ($data['paymode'] == 'Credit Card') {
            $booking_details['first_name']   = $data['first_name'];
            $booking_details['last_name ']   = $data['last_name'];
            $booking_details['postal_code']  = $data['postal_code'];
        }

        $booking_details['country']         = $data['country'];
        
        if( properties::find($data['property_id'])->exp_booking_type == "3" )
        {
            /* $family_id               = Session::get('family_id');
            $family_query               = Package::where('property_id', $data['property_id'])->where('id', $family_id)->first();
            
            if($family_query['title']!="") { $booking_details['title']  = $family_query['title']; } else { $booking_details['title']  = ""; }
            if($family_query['price']!="") { $booking_details['price']  = $family_query['price']; } else { $booking_details['price'] = "";  }
            if($family_query['adults']!=""){ $booking_details['adults'] = $family_query['adults'];  } else {  $booking_details['adults'] = ""; }
            if($family_query['children']!="") { $booking_details['children']    = $family_query['children']; } else { $booking_details['children']=""; }
            if($family_query['infants']!="") {  $booking_details['infants'] = $family_query['infants']; } else { $booking_details['infants']=""; }
            if($family_query['itinerary']!="") { $booking_details['itinerary'] = $family_query['itinerary']; } else { $booking_details['itinerary']=""; } */
            
            if(session('cart'))
            {
                foreach(session('cart') as $id => $details)
                {   
                    DB::table('booking_packages')->insert(
                                     array(
                                            'property_id'     => $data['property_id'], 
                                            'user_id'         => \Auth::user()->id,
                                            'packages_id'     => $id,
                                            'qty'             => $details['quantity'],
                                            'booking_id'      => $booking->id,
                                     )
                                    ); 
                }
            }
        }
        
        
            foreach ($booking_details as $key => $value) {
                $booking_details = new BookingDetails;
                $booking_details->booking_id = $booking->id;
                $booking_details->field = $key;
                $booking_details->value = $value;
                $booking_details->save();
            }
        
        
        do {
            $code = $this->helper->randomCode(6);
            $check_code = BookingTmp::where('code', $code)->get();
        } while (empty($check_code));

        $booking_code = BookingTmp::find($booking->id);

        $booking_code->code = $code;

        $booking_code->save();

        $days = $this->helper->get_days(setDateForDb($data['checkin']), setDateForDb($data['checkout']));

        if ($booking->booking_type == "instant") {
            for ($j=0; $j<count($days)-1; $j++) {
                $tmp_date = date('Y-m-d', strtotime($days[$j]));

                $property_data = [
                    'property_id' => $data['property_id'],
                    'status'      => 'Not available',
                    'price'       => $property_price_temp->original_price($tmp_date),
                    'date'        => $tmp_date,
                ];

                PropertyDates::updateOrCreate(['property_id' => $data['property_id'], 'date' => $tmp_date], $property_data);
            }
        }
       
        if ($booking->status == 'Accepted') {
            $payouts = new Payouts;
            $payouts->booking_id     = $booking->id;
            $payouts->user_id        = $booking->host_id;
            $payouts->property_id    = $booking->property_id;
            $payouts->user_type      = 'host';
            $payouts->amount         = $booking->original_host_payout;
            $payouts->penalty_amount = 0;
            $payouts->currency_code  = $booking->currency_code;
            $payouts->status         = 'Future';

            $payouts->save();
        }

        $message = new Messages;
        $message->property_id    = $data['property_id'];
        $message->booking_id     = $booking->id;
        $message->sender_id      = $booking->user_id;
        $message->receiver_id    = $booking->host_id;
        $message->message        = isset($data['message_to_host']) ? $data['message_to_host'] : '';
        $message->type_id        = 4;
        $message->read           = 0;
        $message->save();

        /*$email_controller = new EmailController;
        $email_controller->booking($booking->id, $checkinDate);
        $email_controller->booking_user($booking->id, $checkinDate);*/


        if ($booking->status =='Accepted') {
            $companyName = Settings::where(['type' => 'general', 'name' => 'name'])->first(['value'])->value;
            $instantBookingConfirm = ($companyName.': ' .'Your booking is confirmed from'.' '. $booking->start_date.' '.'to'.' '.$booking->end_date );
            $instantBookingPaymentConfirm =($companyName.' ' .'Your payment is completed for'.' '.$booking->properties->name);

            twilioSendSms(Auth::user()->formatted_phone, $instantBookingConfirm);
            twilioSendSms(Auth::user()->formatted_phone, $instantBookingPaymentConfirm);

        } else {
            twilioSendSms(Auth::user()->formatted_phone, 'Your booking is initiated, Wait for confirmation');

        }

        Session::forget('payment_property_id');
        Session::forget('payment_checkin');
        Session::forget('payment_checkout');
        Session::forget('payment_number_of_guests');
        Session::forget('payment_booking_type');
        Session::forget('cart');
        return $code;
    }

    public function update($data){
 
        $currencyDefault     = Currency::where('default', 1)->first();
        $property_price_temp = PropertyPrice::where('property_id', $data['property_id'])->first();
        $days                = $this->helper->get_days(setDateForDb($data['checkin']), setDateForDb($data['checkout']));
        $code                = $this->helper->randomCode(6);
        
        $booking = Bookings::find($data['booking_id']);
        $booking->status = 'Accepted';
        $booking->transaction_id = $data['transaction_id'];
        $booking->payment_method_id = $data['payment_method_id'];
        $booking->code = $code;
        $booking->save();

        $email_controller = new EmailController;
        $email_controller->booking($booking->id, $data['checkin']);
        $email_controller->booking_user($booking->id, $data['checkin']);

        $this->addBookingPaymentInHostWallet($booking);
        
        if($booking->payment_method_id=="3")
        {
            $this->minusBookingPaymentInUserWallet($booking);
        }

        $days = $this->helper->get_days(setDateForDb($booking->start_date), setDateForDb($booking->end_date));

        for ($j=0; $j<count($days)-1; $j++) {
            $tmp_date = date('Y-m-d', strtotime($days[$j]));

            $property_data = [
                'property_id' => $booking->property_id,
                'status'      => 'Not available',
                'price'       => $property_price_temp->original_price($tmp_date),
                'date'        => $tmp_date,
            ];

            PropertyDates::updateOrCreate(['property_id' => $booking->property_id, 'date' => $tmp_date], $property_data);
        }

        Bookings::where([['status', 'Processing'], ['property_id', $booking->property_id], ['start_date', $booking->start_date]])->orWhere([['status', 'Pending'], ['property_id', $booking->property_id], ['start_date', $booking->start_date]])->update(['status' => 'Expired']);

       
        
        
        $payouts = new Payouts;
        $payouts->booking_id     = $booking->id;
        $payouts->user_id        = $booking->host_id;
        $payouts->property_id    = $booking->property_id;
        $payouts->user_type      = 'host';
        $payouts->amount         = $booking->original_host_payout;
        $payouts->penalty_amount = 0;
        $payouts->currency_code  = $booking->currency_code;
        $payouts->status         = 'Future';

        $payouts->save();

        $message = new Messages;
        $message->property_id    = $data['property_id'];
        $message->booking_id     = $booking->id;
        $message->sender_id      = $booking->user_id;
        $message->receiver_id    = $booking->host_id;
        $message->message        = isset($data['message_to_host']) ? $data['message_to_host'] : '';
        $message->type_id        = 4;
        $message->read           = 0;
        $message->save();

        BookingDetails::where(['id' => $data['booking_id']])->update(['value' => $data['country']]);
        
        $companyName = Settings::where(['type' => 'general', 'name' => 'name'])->first(['value'])->value;
        $instantBookingConfirm = ($companyName.': ' .'Your booking is confirmed from'.' '. $booking->start_date.' '.'to'.' '.$booking->end_date );
        $instantBookingPaymentConfirm =($companyName.' ' .'Your payment is completed for'.' '.$booking->properties->name);

        twilioSendSms(Auth::user()->formatted_phone, $instantBookingConfirm);
        twilioSendSms(Auth::user()->formatted_phone, $instantBookingPaymentConfirm);

        Session::forget('payment_property_id');
        Session::forget('payment_checkin');
        Session::forget('payment_checkout');
        Session::forget('payment_number_of_guests');
        Session::forget('payment_booking_type');
        Session::forget('payment_booking_status');
        Session::forget('payment_booking_id');

        return $code;   

    }

    public function withdraws(Request $request)
    {
        $photos = Photo::where('user_id', \Auth::user()->id)->get();
        $photo_ids = [];
        foreach ($photos as $key => $value) {
            $photo_ids[] = $value->id;
        }
        $payment_sum = Payment::whereIn('photo_id', $photo_ids)->sum('amount');
        $withdraw_sum = Withdraw::where('user_id', Auth::user()->id)->sum('amount');
        $data['total'] = $total = $payment_sum - $withdraw_sum;
        if ($request->isMethod('post')) {
            if ($total >= $request->amount) {
                $withdraw = new Withdraw;
                $withdraw->user_id = Auth::user()->id;
                $withdraw->amount = $request->amount;
                $withdraw->status = 'Pending';
                $withdraw->save();
                $data['success'] = 1;
                $data['message'] = 'Success';
            } else {
                $data['success'] = 0;
                $data['message'] = 'Balance exceed';
            }
            echo json_encode($data);
            exit;
        }

        $data['details'] = Auth::user()->details_key_value();
        $data['results'] = Withdraw::where('user_id', Auth::user()->id)->get();
        return view('payment.withdraws', $data);
    }
    public function addBookingPaymentInHostWallet($booking)
    {
       /* $walletBalance = Wallet::where('user_id',$booking->host_id)->first();
       $balance = ( $walletBalance->balance + $booking->total - $booking->service_charge - $booking->accomodation_tax - $booking->iva_tax - $booking->host_fee );
       Wallet::where(['user_id' => $booking->host_id])->update(['balance' => $balance]); */
       
        $walletBalance = Wallet::where('user_id',$booking->host_id)->first();
        $default_code  =  Session::get('currency');
        $wallet_code  = Currency::getAll()->firstWhere('id', $walletBalance->currency_id)->code;
        $balance = ( $walletBalance->balance + $this->helper->convert_currency($default_code, $wallet_code, $booking->total)  - $this->helper->convert_currency($default_code, $wallet_code, $booking->service_charge) - $this->helper->convert_currency($default_code, $wallet_code, $booking->accomodation_tax) - $this->helper->convert_currency($default_code, $wallet_code, $booking->iva_tax) - $this->helper->convert_currency($default_code, $wallet_code, $booking->host_fee) );
        Wallet::where(['user_id' => $booking->host_id])->update(['balance' => $balance]);
    }
	
	 public function minusBookingPaymentInUserWallet($booking)
    {
        $walletBalance = Wallet::where('user_id',  Auth::user()->id)->first();
        $default_code  =  Session::get('currency');
        $wallet_code = Currency::getAll()->firstWhere('id', $walletBalance->currency_id)->code;
        
        $balance = ( $walletBalance->balance  - $this->helper->convert_currency($default_code, $wallet_code, $booking->total));
        Wallet::where(['user_id' => Auth::user()->id])->update(['balance' => $balance]);
    }
	
	

}
