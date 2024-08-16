<?php
namespace App\Http\Helpers;

use View;
use Session;
use App\Models\Meta;
use App\Models\Notification;
use App\Models\Permissions;
use App\Models\RoleAdmin;
use App\Models\PermissionRole;
use App\Models\Properties;
use App\Models\PropertyDates;
use App\Models\PropertyPrice;
use App\Models\PropertyFees;
use App\Models\penalty;
use App\Models\Currency;
use App\Models\Bookings;
use App\Models\Settings;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Auth;
use DB;
class Common {

    function __construct()
    {
        setlocale(LC_ALL, 'en_US.UTF8');
    }
    
    function d($var,$a=false)
    {
          echo "<pre>";
          print_r($var);
          echo "</pre>";
          if($a)exit;
    }

    public function content_read($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    public function one_time_message($class, $message)
    { 
        if ($class == 'error') $class = 'danger';
        Session::flash('alert-class', 'alert-'.$class);
        Session::flash('message', $message);
    }

    public static function key_value($key, $value, $ar)
    {
        $ret = [];
        foreach($ar as $k => $v) {
            $ret[$v[$key]] = $v[$value];
        }
        return $ret;
    }

    public function current_action($route)
    {
        $current = explode('@', $route);
        View::share('current_action',$current[1]);
    }

    public static function has_permission($user_id, $permissions = '')
    {
        $permissions      = explode('|', $permissions);
        $user_permissions = Permissions::whereIn('name', $permissions)->get();
        $permission_id = [];
        $i = 0;
        foreach ($user_permissions as $value) {
            $permission_id[$i++] = $value->id;
        }
        $role = RoleAdmin::where('admin_id', $user_id)->first();

        if (count($permission_id) && isset($role->role_id)) {
            $has_permit = PermissionRole::where('role_id', $role->role_id)->whereIn('permission_id', $permission_id);
            return $has_permit->count();
        }
        else return 0;
    }

    public static function meta($url, $field)
    {
        $metas = Meta::where('url', $url);

        if($metas->count())
            return $metas->first()->$field;
        else if($field == 'title')
            return 'Page Not Found';
        else
            return '';
    }

    public function vrCacheForget($key)
    {
        Cache::forget($key);
    }

    function backup_tables($host,$user,$pass,$name,$tables = '*')
    {
        try {
            $con = mysqli_connect($host,$user,$pass,$name);
        } catch (Exception $e) {
            
        }

        if (mysqli_connect_errno()) {
            $this->one_time_message('danger', "Failed to connect to MySQL: ".mysqli_connect_error());
            return 0;
        }
        
        if ($tables == '*') {
             $tables = array();
             $result = mysqli_query($con, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        
        $return = '';
        foreach($tables as $table) {
            $result = mysqli_query($con, 'SELECT * FROM '.$table);
            $num_fields = mysqli_num_fields($result);
            
            
            $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE '.$table));
            $return.= "\n\n".str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $row2[1]).";\n\n";
            
            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j < ($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }

            $return.="\n\n\n";
        }
        
        $backup_name = date('Y-m-d-His').'.sql';
        
        $handle = fopen(storage_path("db-backups").'/'.$backup_name,'w+');
        fwrite($handle,$return);
        fclose($handle);

        return $backup_name;
    }

    public function add_notification($user_id, $message)
    {
        $notification = new Notification;
        $notification->user_id = $user_id;
        $notification->message = $message;
        $notification->status = 'unread';
        $notification->save();
    }

    public static function thousandsCurrencyFormat($num) 
    {
      if($num < 1000) return $num;
      $x = round($num);
      $x_number_format = number_format($x);
      $x_array = explode(',', $x_number_format);
      $x_parts = array('k', 'm', 'b', 't');
      $x_count_parts = count($x_array) - 1;
      $x_display = $x;
      $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
      $x_display .= $x_parts[$x_count_parts - 1];
      return $x_display;
    }

    public function senitize($val)
    {
        $inp = trim($val);
        $inp = strip_tags($inp);
        $inp = htmlspecialchars($inp);
        return $inp;
    }

    public function pretty_url($str)
    {
        /* $url = $this->convert_to_url_friendly($str);
        $turl = $url;
        $i = 0;
        while(1){
            $i++;
            $cnt = Properties::where('slug', $turl)->count();
            if($cnt != 0)
                $turl = $url.'-'.$i;
            else break;
        } */
        
        $url = $this->convert_to_url_friendly($str);
        $turl = $url;
        $i = 0;
            $cnt = Properties::where('slug', $turl)->count();
            if($cnt == 0)
            {
                $turl = $url;
            }
            
        
        return $turl;
    } 
     public function pretty_url1($str)
    {
        $url = $this->convert_to_url_friendly($str);
        $turl = $url;
        $i = 0;
        while(1){
            $i++;
            $cnt = Properties::where('slug', $turl)->count();
            if($cnt != 0)
                $turl = $url.'-'.$i;
            else break;
        } 
        
        return $turl;
    } 
    
    public function convert_to_url_friendly($str, $replace=array(), $delimiter='-') 
    {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    public function currency_rate($from, $to)
    {
        $from_rate = Currency::whereCode($from)->first()->rate;
        $to_rate   = Currency::whereCode($to)->first()->rate;

        return round($from_rate / $to_rate);
    }

     public function convert_currency($from = '', $to = '', $price = '')
    {
        if ($from == '') {
            if (Session::get('currency'))
                $from = Session::get('currency');
            else
                $from = Currency::where('default', 1)->first()->code;
        }

        if($to == '') {
            if(Session::get('currency'))
               $to = Session::get('currency');
            else
               $to = Currency::where('default', 1)->first()->code;
        }

        $rate        = Currency::where('code', $from)->first()->rate;
        $price       = str_replace(']','',$price);//For Php Version 7.2
        
        $price = round($price, 2);
        $base_amount = $price / $rate;
        $session_rate = Currency::where('code', $to)->first()->rate;
        return round($base_amount * $session_rate);
    } 
    
    
    /* function convert_currency($from = '', $to = '', $price='') {

    $from = currentCurrency($from);
    $to = currentCurrency($to);
    $price       = str_replace(']','',$price);//For Php Version 7.2
    $base_amount = (float) $price / $from->rate;
    return round($base_amount * $to->rate, $to->rate > 1000 ? 0 : 2);
} */


    public function prev_get_price($property_id, $checkin, $checkout, $guest_count)
    {
        // $from                       = Session::get('date_separator') == '/' ? date_create(strtotime($checkin)) : date_create(strtotime($checkin));
        // $to                         = Session::get('date_separator') == '/' ? date_create(strtotime($checkout)) : date_create(strtotime($checkout));
        // if (Session::get('date_separator') == '/') {
        //     $from =  date_create(strtotime($checkin));
        //     $to   =  date_create(strtotime($checkout));
        // } else if (Session::get('date_separator') == '-') {
        //    $from =  date_create(strtotime($checkin));
        //    $to   =  date_create(strtotime($checkout));
        // } else if (Session::get('date_separator') == '.') {
        //    $from =  date_create(strtotime($checkin));
        //    $to   =  date_create(strtotime($checkout));
        // } else {
        //     echo "there is no separator";
        // }
    
        // $datetime1 = new DateTime($from);
        // $datetime2 = new DateTime($to);
        // $diff      = $datetime1->diff($datetime2);
        // // $diff                       = date_diff($from,$to);
        // $total_nights               = $diff->format("%a");
        $date1                      = date('Y-m-d', strtotime($checkin));
        $enddate                    = date('Y-m-d', strtotime($checkout));
        $date2                      = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($enddate ) ) ));
        $days                       = $this->get_days($date1, $date2 );
        $dates_not_avilable         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Not available')->get();
        $different_price            = 0;
        if ($dates_not_avilable->count() > 0) {
            $result['status'] = "Not available";
            return json_encode($result);
        }

        $different_price_dates_original = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('price', 'date')->toArray();
        $different_price_dates_ar   = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('date')->toArray();


        $properties                 = Properties::find($property_id);
        $price_details              = $properties->property_price;
        $different_price            = 0;
        $weekend_price              = 0;
        $night_price                = 0;
        $total_price                = 0;

        $weeks_count = 0;
        $month_count = 0;
        $day_count = 0;
        $month_price = 0;
        $week_price = 0;
        $temp_total = 0;

        $currencyDefault    = Currency::where('default', 1)->first();
        $different_price_dates = [];
        $different_price_dates_default_curr = [];
        foreach ($days as $value) {
            if (in_array($value, $different_price_dates_ar)) {
                $different_price += $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates_original[$value]);
                $different_price_dates[$value] = $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates_original[$value]);
                $different_price_dates_default_curr[$value] = $this->convert_currency($properties->property_price->currency_code,$currencyDefault->code, $different_price_dates_original[$value]);

            } else if (date('N', strtotime($value)) == 5 && $price_details->weekend_price != 0) {
                $weekend_price += $price_details->weekend_price;
            } else if (date('N', strtotime($value)) == 6 && $price_details->weekend_price != 0) {
                $weekend_price += $price_details->weekend_price;
            } else {
                $night_price += $price_details->price;
            }
            //echo $weekend_price;exit();
            $day_count++;
            if ($day_count%30 == 0 && $price_details->monthly_discount != 0) {
                $month_count++;
                $weeks_count = 0;
                $week_price = 0;
                $temp_total = $different_price+$weekend_price+$night_price;
                $month_price = $temp_total;
            } else if (($day_count-($month_count*28))%7 == 0 && $price_details->weekly_discount != 0){
                $weeks_count++;
                $temp_total = $different_price+$weekend_price+$night_price;
                $week_price = $temp_total-$month_price;
            }
        }

        $result['different_price_dates'] = $different_price_dates;
        $result['different_price_dates_default_curr'] = $different_price_dates_default_curr;

        $property_fees          = PropertyFees::pluck('value', 'field');
        $remaining_day_price    = ($different_price + $weekend_price + $night_price)-$month_price-$week_price;
        /*echo $week_price;
        echo $remaining_day_price;*/
        $result['total_night_price'] = $remaining_day_price+$month_price+$week_price;
        $result['discount'] = round(($month_price*$price_details->monthly_discount)/100) + round(($week_price*$price_details->weekly_discount)/100);
        
        //$result['total_night_price'] = $remaining_day_price+$month_price-round(($month_price*$price_details->monthly_discount)/100)+$week_price-round(($week_price*$price_details->weekly_discount)/100);

        
        // $result['property_price']    = round($result['total_night_price'] / $day_count);
        $result['property_price']   = $price_details->price;
        $result['total_nights']     = $day_count;
        $result['service_fee']      = number_format(($property_fees['guest_service_charge'] / 100) * $result['total_night_price']);
        $result['host_fee']         = number_format(($property_fees['host_service_charge'] / 100) * $result['total_night_price']);
        $result['additional_guest']  = 0;
        $result['security_fee']      = 0;
        $result['cleaning_fee']      = 0;

        if ($guest_count > $price_details->guest_after) {
          $additional_guest_count     = $guest_count - $price_details->guest_after;
          $result['additional_guest'] = $additional_guest_count * $price_details->guest_fee;
        }
          
        if ($price_details->security_fee)
           $result['security_fee']     = $price_details->security_fee;
          
        if ($price_details->cleaning_fee)      
           $result['cleaning_fee']     = $price_details->cleaning_fee;

        //$result['total']            = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'];
        //$result['subtotal']         = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'];

        $result['total']            = floatval(preg_replace('/[^\d.]/', '',$result['service_fee'])) + floatval(preg_replace('/[^\d.]/', '',$result['total_night_price'])) + floatval(preg_replace('/[^\d.]/', '',$result['additional_guest'])) + floatval(preg_replace('/[^\d.]/', '',$result['security_fee'] + $result['cleaning_fee'] - $result['discount']));
        $result['subtotal']         = floatval(preg_replace('/[^\d.]/', '',$result['service_fee'])) + floatval(preg_replace('/[^\d.]/', '',$result['total_night_price'])) + floatval(preg_replace('/[^\d.]/', '',$result['additional_guest'])) + floatval(preg_replace('/[^\d.]/', '',$result['security_fee'] + $result['cleaning_fee'] - $result['discount']));

        //property currency
        //$result['currency'] = $price_details->currency_code;
        //default_currency or home_currency(Admin)
        //$result['currency'] = Currency::where('default', 1)->first()->code;    
       
        $result['total_night_price_with_symbol'] = moneyFormat($properties->property_price->currency->symbol,$result['total_night_price']);
           ;
        $result['service_fee_with_symbol']      = moneyFormat($properties->property_price->currency->symbol,$result['service_fee']);
        $result['total_with_symbol']            = moneyFormat($properties->property_price->currency->symbol,$result['total']);
        $result['currency']						= Session::get('currency');
           
        return json_encode($result);
    }

    public function get_price($property_id, $checkin, $checkout, $guest_count, $time_slot = '0')
    {
		$properties                 = Properties::find($property_id);
		if($properties->type=="experience")
		{
			$checkInDate                = setDateForDb($checkin);
			$checkOutDate               = setDateForDb($checkout);
			$from                       = new DateTime($checkInDate);
			$to                         = new DateTime($checkOutDate);
			$diff                       = date_diff($from,$to);
			$total_nights               = $diff->format("%a");
			$date1                      = date('Y-m-d', strtotime($checkInDate));
			$enddate                    = date('Y-m-d', strtotime($checkOutDate));
			$date2                      = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($enddate ) ) ));
			$days                       = $this->get_days($date1, $date2 );
			$dates_not_avilable         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Not available')->get();
			$different_price            = 0;
			if($dates_not_avilable->count() > 0){
				$result['status'] = "Not available";
				return json_encode($result);
			}
			
			 /* $req_book_count  =  Bookings::where('property_id', $property_id)->where('start_date', $date1)->where('user_id', Auth::user()->id)->where('status', 'Pending')->count();
            if($req_book_count > 0){
				$result['status'] = "Not available";
				return json_encode($result);
			} */
			
			$people_count        =  Bookings::where(['property_id' => $property_id])->where('start_date', $date1)->where('status', 'Accepted')->sum('guest');
			$max_people    		 = $properties->accommodates;
			
			if($properties->exp_booking_type == "1")
			{
				if($max_people < $people_count+$guest_count)
				{	
					$result['status'] = "Not available";
					return json_encode($result);
				}
			}	
			
			if($properties->exp_booking_type == "2")
			{
				$people_count1        =  Bookings::where(['property_id' => $property_id])->where('start_date', $date1)->where('status', 'Accepted')->where('time_slot', $time_slot)->sum('guest');
				if($max_people < $people_count1+$guest_count)
				{	
					$result['status'] = "Not available";
					return json_encode($result);
				}
			}			
			$different_price_dates      = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('price', 'date')->toArray();
			$different_price_dates_ar   = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('date')->toArray();
		   
			$price_details              = $properties->property_price;
			$different_price            = 0;
			$weekend_price              = 0;
			$night_price                = 0;
			$total_price                = 0;

			$weeks_count = 0;
			$month_count = 0;
			$day_count   = 0;
			$month_price = 0;
			$week_price  = 0;
			$temp_total  = 0;
			
			foreach ($days as $value) {
				if(in_array($value, $different_price_dates_ar)){
					//$different_price += $different_price_dates[$value];
					$different_price += $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates[$value]);

				}else if(date('N', strtotime($value)) == 5 && $price_details->weekend_price != 0){
					$weekend_price += $price_details->weekend_price;
				}else if(date('N', strtotime($value)) == 6 && $price_details->weekend_price != 0){
					$weekend_price += $price_details->weekend_price;
				}else{
					$night_price += $price_details->price;
				}
				//echo $weekend_price;exit();
				$day_count++;
				if($day_count%30 == 0 && $price_details->monthly_discount != 0){
					$month_count++;
					$weeks_count = 0;
					$week_price = 0;
					$temp_total = $different_price+$weekend_price+$night_price;
					$month_price = $temp_total;
				}else if(($day_count-($month_count*28))%7 == 0 && $price_details->weekly_discount != 0){
					$weeks_count++;
					$temp_total = $different_price+$weekend_price+$night_price;
					$week_price = $temp_total-$month_price;
				}
			}

			foreach ($days as $key => $value) {

				if(in_array($value, $different_price_dates_ar)){

					//$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $different_price_dates[$value]);
					$aa = $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates[$value]);
					$allDate[$key]['price'] 		= moneyFormat($properties->property_price->currency->symbol, $aa);
					
					$allDate[$key]['original_price'] = $different_price_dates[$value];

				}else if(date('N', strtotime($value)) == 5 && $price_details->weekend_price != 0){

					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $price_details->weekend_price);
					$allDate[$key]['original_price'] = $price_details->weekend_price;

				}else if(date('N', strtotime($value)) == 6 && $price_details->weekend_price != 0){

					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $price_details->weekend_price);

					$allDate[$key]['original_price'] = $price_details->weekend_price;

				}else{
					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $properties->property_price->price);
					$allDate[$key]['original_price'] = $properties->property_price->price;
				}

				$allDate[$key]['date'] = onlyFormat($value);

			}

			$result['date_with_price'] = $allDate;



			$dates_min_stay_avilable         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('min_stay', 1)->get();

			$min_day         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('min_stay', 1)->max('min_day');

			if($dates_min_stay_avilable->count() > 0){

				if ($day_count < $min_day) 
				{
					$result['status']  = 'minimum stay';
					$result['minimum'] = $min_day;
					return json_encode($result);
				}                     
			}

			/* maximum day */
			
			$dates_max_stay_avilable = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('max_stay', 1)->get();
			$max_day                 = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('max_stay', 1)->max('max_day');
			if($dates_max_stay_avilable->count() > 0){
				if ($day_count > $max_day) 
				{
					$result['status']  = 'maximum stay';
					$result['maximum'] = $max_day;
					return json_encode($result);
				}                     
			}
			

			$property_fees          = PropertyFees::pluck('value', 'field');
			$remaining_day_price    = ($different_price + $weekend_price + $night_price)-$month_price-$week_price;

			$result['total_night_price'] = $remaining_day_price+$month_price+$week_price;
			
			$result['discount'] = round(($month_price*$price_details->monthly_discount)/100) + round(($week_price*$price_details->weekly_discount)/100);
			
			$result['property_price']   = round($result['total_night_price'] / $day_count);
			$result['total_nights']     = $day_count;
			$result['additional_guest']  = 0;
			$result['security_fee']      = 0;
			$result['cleaning_fee']      = 0;

			if($guest_count > $price_details->guest_after)
			{
			  $additional_guest_count     = $guest_count - $price_details->guest_after;
			  $result['additional_guest'] = $additional_guest_count * $price_details->guest_fee * $day_count;
			}
			  
			if($price_details->security_fee)
			  $result['security_fee']     = $price_details->security_fee;
			  
			if($price_details->cleaning_fee)      
			  $result['cleaning_fee']     = $price_details->cleaning_fee;
		  		    			  
			if($properties->exp_booking_type == "3")
			{   
			    
    			     if(session('cart'))
    			     {
    			        $svtotal="0";
                        foreach(session('cart') as $id => $details)
                        {
                            if($properties->id == $details['property_id'])
                            {
                                $svtotal +=  $details['quantity'] * $details['price'];
                            }
                        }
                        $svtotal        = $this->convert_currency($properties->property_price->currency_code, '', $svtotal);
                        $packages_price = $svtotal;
    			     }
    			     else
    			     {
    			        $packages_price = $time_slot; 
    			     }
			    
			    
			    $result['total_nights']     = trans('messages.experience.packages'). ' '.trans('messages.experience.price');
			    $result['service_fee']      = round(($property_fees['guest_service_charge'] / 100) * $packages_price);
			    $result['host_fee']         = round(($property_fees['host_service_charge'] / 100) * $packages_price);
			    $result['iva_tax']          = round(($property_fees['iva_tax'] / 100) * $packages_price);
			    $result['accomodation_tax'] = round(($property_fees['accomodation_tax'] / 100) * $packages_price);
			
    			$sfee  = round(($property_fees['guest_service_charge'] / 100) * $packages_price);
    			$iva   = round(($property_fees['iva_tax'] / 100) * $packages_price);
			    $accomodatntax = round(($property_fees['accomodation_tax'] / 100) * $packages_price);
			    
				$result['total']     = $sfee + $packages_price + $iva + $accomodatntax;
				$result['subtotal']  = $sfee + $packages_price + $iva + $accomodatntax;
				
				$result['total_night_price_with_symbol'] = moneyFormat($properties->property_price->currency->symbol,$packages_price);
    			$result['service_fee_with_symbol']       = moneyFormat($properties->property_price->currency->symbol,$result['service_fee']);
    			$result['total_with_symbol']             = moneyFormat($properties->property_price->currency->symbol,$result['total']);
    			$result['iva_tax_with_symbol']           = moneyFormat($properties->property_price->currency->symbol,$result['iva_tax']);
    			$result['accomodation_tax_with_symbol']  = moneyFormat($properties->property_price->currency->symbol,$result['accomodation_tax']);
    			
			}
			elseif($properties->exp_booking_type == "2")
			{
			    $date_with_price = $result['total_night_price'] * $guest_count;
			    $result['total_nights']     = $guest_count." ".trans('messages.header.guest');
			     
			    $result['service_fee']      = number_format(($property_fees['guest_service_charge'] / 100) * $date_with_price);
			    $result['host_fee']         = number_format(($property_fees['host_service_charge'] / 100) * $date_with_price);
			    $result['iva_tax']          = number_format(($property_fees['iva_tax'] / 100) * $date_with_price);
			    $result['accomodation_tax'] = number_format(($property_fees['accomodation_tax'] / 100) * $date_with_price);
			
			    
			    $sfee            = round(($property_fees['guest_service_charge'] / 100) * $date_with_price);
    			$iva             = round(($property_fees['iva_tax'] / 100) * $date_with_price);
			    $accomodatntax   = round(($property_fees['accomodation_tax'] / 100) * $date_with_price);
			    
				$result['total']     = $sfee + $date_with_price + $result['security_fee'] + $iva + $accomodatntax - $result['discount'];
				$result['subtotal']  = $sfee + $date_with_price + $result['security_fee'] + $iva + $accomodatntax - $result['discount'];
				
				$result['total_night_price_with_symbol'] = moneyFormat($properties->property_price->currency->symbol,$date_with_price);
    			$result['service_fee_with_symbol']       = moneyFormat($properties->property_price->currency->symbol,$result['service_fee']);
    			$result['total_with_symbol']             = moneyFormat($properties->property_price->currency->symbol,$result['total']);
    			$result['iva_tax_with_symbol']           = moneyFormat($properties->property_price->currency->symbol,$result['iva_tax']);
    			$result['accomodation_tax_with_symbol']  = moneyFormat($properties->property_price->currency->symbol,$result['accomodation_tax']);

			}
			else
			{
			    $date_price = $result['total_night_price'] * $guest_count;
			    $result['total_nights']     = $guest_count." ".trans('messages.header.guest');
			    
			    $result['service_fee']      = number_format(($property_fees['guest_service_charge'] / 100) * $date_price);
    			$result['host_fee']         = number_format(($property_fees['host_service_charge'] / 100) * $date_price);
    			$result['iva_tax']          = number_format(($property_fees['iva_tax'] / 100) * $date_price);
    			$result['accomodation_tax'] = number_format(($property_fees['accomodation_tax'] / 100) * $date_price);
    			
			    
			    $sfee  = round(($property_fees['guest_service_charge'] / 100) * $date_price);
			    $iva   = round(($property_fees['iva_tax'] / 100) * $date_price);
			    $accomodatntax = round(($property_fees['accomodation_tax'] / 100) * $date_price);
			
			
			    $result['total']     = $sfee + $result['total_night_price'] * $guest_count + $result['security_fee'] + $iva + $accomodatntax - $result['discount'];
				$result['subtotal']  = $sfee + $result['total_night_price'] * $guest_count + $result['security_fee'] + $iva + $accomodatntax - $result['discount'];
				
				$result['total_night_price_with_symbol'] = moneyFormat($properties->property_price->currency->symbol,$date_price);
    			$result['service_fee_with_symbol']       = moneyFormat($properties->property_price->currency->symbol,$result['service_fee']);
    			$result['total_with_symbol']             = moneyFormat($properties->property_price->currency->symbol,$result['total']);
    			$result['iva_tax_with_symbol']           = moneyFormat($properties->property_price->currency->symbol,$result['iva_tax']);
    			$result['accomodation_tax_with_symbol']  = moneyFormat($properties->property_price->currency->symbol,$result['accomodation_tax']);

			}
			
			//$result['total']            = $sfee + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] + $iva + $accomodatntax - $result['discount'];
			//$result['subtotal']         = $sfee + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] +$iva + $accomodatntax - $result['discount'];

			
			$result['currency']                      = Session::get('currency');
		}
		else
		{
			$checkInDate                = setDateForDb($checkin);
			$checkOutDate               = setDateForDb($checkout);
			$from                       = new DateTime($checkInDate);
			$to                         = new DateTime($checkOutDate);
			$diff                       = date_diff($from,$to);
			$total_nights               = $diff->format("%a");
			$date1                      = date('Y-m-d', strtotime($checkInDate));
			$enddate                    = date('Y-m-d', strtotime($checkOutDate));
			$date2                      = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($enddate ) ) ));
			$days                       = $this->get_days($date1, $date2 );
			$dates_not_avilable         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Not available')->get();
			$different_price            = 0;
			if($dates_not_avilable->count() > 0){
				$result['status'] = "Not available";
				return json_encode($result);
			}
			
			if(Auth::check())
			{
    			$req_book_count  =  Bookings::where('property_id', $property_id)->where('start_date', $date1)->where('user_id', Auth::user()->id)->where('status', 'Pending')->count();
                if($req_book_count > 0){
    				$result['status'] = "Not available";
    				return json_encode($result);
    			}
			}
			
			$different_price_dates      = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('price', 'date')->toArray();
			$different_price_dates_ar   = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('status', 'Available')->pluck('date')->toArray();
		   
			$price_details              = $properties->property_price;
			$different_price            = 0;
			$weekend_price              = 0;
			$night_price                = 0;
			$total_price                = 0;

			$weeks_count = 0;
			$month_count = 0;
			$day_count = 0;
			$month_price = 0;
			$week_price = 0;
			$temp_total = 0;
			
			foreach ($days as $value) {
				if(in_array($value, $different_price_dates_ar)){
					//$different_price += $different_price_dates[$value];
					$different_price += $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates[$value]);

				}else if(date('N', strtotime($value)) == 5 && $price_details->weekend_price != 0){
					$weekend_price += $price_details->weekend_price;
				}else if(date('N', strtotime($value)) == 6 && $price_details->weekend_price != 0){
					$weekend_price += $price_details->weekend_price;
				}else{
					$night_price += $price_details->price;
				}
				//echo $weekend_price;exit();
				$day_count++;
				if($day_count%30 == 0 && $price_details->monthly_discount != 0){
					$month_count++;
					$weeks_count = 0;
					$week_price = 0;
					$temp_total = $different_price+$weekend_price+$night_price;
					$month_price = $temp_total;
				}else if(($day_count-($month_count*28))%7 == 0 && $price_details->weekly_discount != 0){
					$weeks_count++;
					$temp_total = $different_price+$weekend_price+$night_price;
					$week_price = $temp_total-$month_price;
				}
			}

			foreach ($days as $key => $value) {

				if(in_array($value, $different_price_dates_ar)){

					//$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $different_price_dates[$value]);
					$aa = $this->convert_currency($properties->property_price->currency_code, '', $different_price_dates[$value]);
					$allDate[$key]['price'] 		= moneyFormat($properties->property_price->currency->symbol, $aa);
					
					$allDate[$key]['original_price'] = $different_price_dates[$value];

				}else if(date('N', strtotime($value)) == 5 && $price_details->weekend_price != 0){

					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $price_details->weekend_price);
					$allDate[$key]['original_price'] = $price_details->weekend_price;

				}else if(date('N', strtotime($value)) == 6 && $price_details->weekend_price != 0){

					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $price_details->weekend_price);

					$allDate[$key]['original_price'] = $price_details->weekend_price;

				}else{
					$allDate[$key]['price'] = moneyFormat($properties->property_price->currency->symbol, $properties->property_price->price);
					$allDate[$key]['original_price'] = $properties->property_price->price;

				}

				$allDate[$key]['date'] = onlyFormat($value);

			}

			$result['date_with_price'] = $allDate;



			$dates_min_stay_avilable         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('min_stay', 1)->get();

			$min_day         = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('min_stay', 1)->max('min_day');

			if($dates_min_stay_avilable->count() > 0){

				if ($day_count < $min_day) 
				{
					$result['status']  = 'minimum stay';
					$result['minimum'] = $min_day;
					return json_encode($result);
				}                     
			}

			/* maximum day */
			
			$dates_max_stay_avilable = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('max_stay', 1)->get();
			$max_day                 = PropertyDates::where(['property_id' => $property_id])->whereIn('date', $days)->where('max_stay', 1)->max('max_day');
			if($dates_max_stay_avilable->count() > 0){
				if ($day_count > $max_day) 
				{
					$result['status']  = 'maximum stay';
					$result['maximum'] = $max_day;
					return json_encode($result);
				}                     
			}
			

			$property_fees          = PropertyFees::pluck('value', 'field');
			$remaining_day_price    = ($different_price + $weekend_price + $night_price)-$month_price-$week_price;

			$result['total_night_price'] = $remaining_day_price+$month_price+$week_price;
			
			//print_r($result['total_night_price']);exit;
			
			$result['discount'] = round(($month_price*$price_details->monthly_discount)/100) + round(($week_price*$price_details->weekly_discount)/100);
			

		   // $result['total_night_price'] = $remaining_day_price+$month_price-round(($month_price*$price_details->monthly_discount)/100)+$week_price-round(($week_price*$price_details->weekly_discount)/100);
			
			$result['property_price']   = round($result['total_night_price'] / $day_count);
			$result['total_nights']     = $day_count;
			$result['service_fee']      = number_format(($property_fees['guest_service_charge'] / 100) * $result['total_night_price']);
			$result['host_fee']         = number_format(($property_fees['host_service_charge'] / 100) * $result['total_night_price']);
			$result['iva_tax']          = number_format(($property_fees['iva_tax'] / 100) * $result['total_night_price']);
			$result['accomodation_tax'] = number_format(($property_fees['accomodation_tax'] / 100) * $result['total_night_price']);
			$result['additional_guest']  = 0;
			$result['security_fee']      = 0;
			$result['cleaning_fee']      = 0;

			if($guest_count > $price_details->guest_after)
			{
			  $additional_guest_count     = $guest_count - $price_details->guest_after;
			  $result['additional_guest'] = $additional_guest_count * $price_details->guest_fee * $day_count;
			}
			  
			if($price_details->security_fee)
			  $result['security_fee']     = $price_details->security_fee;
			  
			if($price_details->cleaning_fee)      
			  $result['cleaning_fee']     = $price_details->cleaning_fee;
			  
			$sfee  = round(($property_fees['guest_service_charge'] / 100) * $result['total_night_price']);
			$iva   = round(($property_fees['iva_tax'] / 100) * $result['total_night_price']);
			$accomodatntax = round(($property_fees['accomodation_tax'] / 100) * $result['total_night_price']);
			

			/* $result['total']         = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] + $result['iva_tax'] + $result['accomodation_tax'] - $result['discount'];
			$result['subtotal']         = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] +$result['iva_tax'] + $result['accomodation_tax'] - $result['discount']; */
		  
			$result['total']            = $sfee + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] + $iva + $accomodatntax - $result['discount'];
			$result['subtotal']         = $sfee + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] +$iva + $accomodatntax - $result['discount'];

			//$result['currency']         = $price_details->currency_code;
			$result['total_night_price_with_symbol'] = moneyFormat($properties->property_price->currency->symbol,$result['total_night_price']);
			$result['service_fee_with_symbol']       = moneyFormat($properties->property_price->currency->symbol,$result['service_fee']);
			$result['total_with_symbol']             = moneyFormat($properties->property_price->currency->symbol,$result['total']);
			$result['iva_tax_with_symbol']           = moneyFormat($properties->property_price->currency->symbol,$result['iva_tax']);
			$result['accomodation_tax_with_symbol']             = moneyFormat($properties->property_price->currency->symbol,$result['accomodation_tax']);

			$result['currency']                      = Session::get('currency');
		}
		
        
        return json_encode($result);
    }
    
    public function get_days($startDate, $endDate)
    {           
        $days []     = $startDate;
        $startDate   = is_numeric($startDate) ? $startDate : strtotime($startDate);
        $endDate     = is_numeric($endDate) ? $endDate : strtotime($endDate);
      
        $startDate   = gmdate("Y-m-d", $startDate);  
        $endDate     = gmdate("Y-m-d", $endDate);  
        $currentDate = $startDate;
        while($currentDate < $endDate) {
            $currentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
            $days[]      = $currentDate;  
        }         
        return $days;  
    }

    public function y_m_d_convert($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public function host_penalty_check($penalty, $booking_amount,$currency_code)
    {
        $penalty_id = '';
        $penalty_amnt = '';

        $penalty_sum = 0;
        if ($penalty->count() > 0 ) {
       
            $host_amount = $booking_amount;

            foreach ($penalty as $pen) {

                $host_amount = $this->convert_currency($currency_code,$pen->currency_code,$host_amount);
              
                $remaining_penalty = $pen->remaining_penalty;

                if ($host_amount > $remaining_penalty) {

                    $host_amount = $host_amount - $remaining_penalty ;
          
                    $penalty = Penalty::find($pen->id);
                    $penalty->remaining_penalty     = 0;
                    $penalty->status                = "Completed";
                    $penalty->save();

                    $penalty_id .= $pen->id.',';
                    $penalty_amnt .= $remaining_penalty.',';
                    $penalty_sum += $remaining_penalty;
                } else {

                    $amount_reamining = $remaining_penalty - $host_amount;

                    $penalty = Penalty::find($pen->id);

                    $penalty->remaining_penalty  = $amount_reamining;
                
                    $penalty->save();

                    $penalty_id .= $pen->id.',';
                    $penalty_amnt .= $host_amount.',';
                    $penalty_sum += $host_amount;
                    $host_amount = 0;
                }

                $host_amount = $this->convert_currency($pen->currency_code,$booking_amount,$host_amount);
            }

            $penalty_amnt   = rtrim($penalty_amnt, ',');
            $penalty_id     = rtrim($penalty_id, ',');
        } else {
            $host_amount = $booking_amount;

            $penalty_id  = 0;
            $penalty_amnt = '';
            $penalty_sum = 0;
        }

        $result['host_amount']     = $host_amount;
        $result['penalty_ids']     = $penalty_id;
        $result['penalty_total']   = $penalty_sum;
        $result['panalty_amounts'] = $penalty_amnt;

        return $result;
    }

    function randomCode($length=20)
    {
        $var_num = 3;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num_set = '0123456789';
        $low_ch_set = 'abcdefghijklmnopqrstuvwxyz';
        $high_ch_set = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomString = '';

        $randomString .= $num_set[rand(0, strlen($num_set) - 1)];
        $randomString .= $low_ch_set[rand(0, strlen($low_ch_set) - 1)];
        $randomString .= $high_ch_set[rand(0, strlen($high_ch_set) - 1)];
        
        for ($i = 0; $i < $length-$var_num; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        $randomString = str_shuffle($randomString);

        return $randomString; 
    }

    public static function dateRange($startDate, $endDate, $step = '+1 day', $format = 'Y-m-d') 
    {
        $dates   = array();
        $current = strtotime($startDate);
        $endDate = strtotime($endDate);
        if ($current > $endDate) {
            return $dates;
        }
        while( $current <= $endDate ) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }
    
     /* public static function randomfunction()
     {
        require_once("SCRIPT/apl_core_configuration.php");
        require_once("SCRIPT/apl_core_functions.php");
        
        $LICENSE_CODE = env("LICENSE"); 
        $ROOT_URL = url('/');
        $CLIENT_EMAIL = "";
        
        $res = Settings::where('type', 'general')->Where('name', 'aplInstallLicense')->first();
        
        if($res->value == "no")
        {
            $license_notifications_array=aplInstallLicense($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
            if ($license_notifications_array['notification_case']=="notification_license_ok") 
            {
                //$demo_page_message="Demo Script (Minimal) is installed and ready to use!";
                
                Settings::where(['name' => 'aplInstallLicense'])->update(['value' => 'Yes']);
                $demo_page_message="success";
                return $demo_page_message;
            }
            else 
            {
                $demo_page_message="Demo Script (Minimal) installation failed because of this reason: ".$license_notifications_array['notification_text'];
                
                $ROOT_URL = url('/').'/randam/code?txt='.$demo_page_message;
                return redirect()->away($ROOT_URL)->send();
            } 
        }
        else
        {
             $demo_page_message="success";
            return $demo_page_message;
        }
     }
     
     public static function randomfunction1()
     {
        require_once("SCRIPT/apl_core_configuration.php");
        require_once("SCRIPT/apl_core_functions.php");
        
        $license_notifications_array=aplVerifyLicense(null, 1); //$FORCE_VERIFICATION value set to 1 in this script for demo purposes only. A value of 0 should always be used in real-life scripts.
            
        if($license_notifications_array['notification_case']=="notification_license_ok") //'notification_license_ok' case returned - operation succeeded
        {
            $demo_page_message="Demo Script (Minimal) license verification succeeded!";
            return $demo_page_message;
        }
        else //Other case returned - operation failed
        {
            $demo_page_message="Demo Script (Minimal) license verification failed because of this reason: ".$license_notifications_array['notification_text'];
            
            $ROOT_URL = url('/').'/randam/code?txt='.$demo_page_message;
            return redirect()->away($ROOT_URL)->send();
        }
     } */

}