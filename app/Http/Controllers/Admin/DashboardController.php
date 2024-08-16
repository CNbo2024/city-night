<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator; 
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;

use App\DataTables\ReportsDataTable;
use App\Http\Helpers\Common;

use App\Models\{
    User,
    Properties,
    Bookings,
    Currency,
	Reports
};

class DashboardController extends Controller
{
    protected $helper;

	public function __construct(Reports $report)
    {
        $this->helper = new Common;
        $this->report = $report;
    }
    
    public function index()
    {
        $data['total_users_count']        = User::count();
        $data['total_property_count']     = Properties::where('type', 'property')->count();
        $data['total_reservations_count'] = Bookings::count();

        $data['today_users_count']        = User::whereDate('created_at', DB::raw('CURDATE()'))->count();
        $data['today_property_count']     = Properties::whereDate('created_at', DB::raw('CURDATE()'))->count();
        $data['today_experience_count']   = Properties::whereDate('created_at', DB::raw('CURDATE()'))->where('type', 'experience')->count();
        $data['total_experience_count']     = Properties::where('type', 'experience')->count();

        $data['today_reservations_count'] = Bookings::whereDate('created_at', DB::raw('CURDATE()'))->count();

        $properties = new Properties;
        $data['propertiesList']      = $properties->getLatestProperties();
        $data['experience']          = $properties->getLatestExperience();
		//$data['experience']    	 = Properties::where('status', '=', 'Listed')->where('type', 'experience')->where('admin_approval', '=', '1')->limit(8)->orderBy('id', 'DESC')->get();

        $bookings = new Bookings;
        $data['bookingList'] = $bookings->getBookingLists(); 
        
        $data['default_cur_code'] = Currency::where('default', 1)->first();

        $months = $tempMonths = $monthsNumber = $monthlyNights = array();

        $j = 11;
        for ($i = 0; $i < 12; $i++) {
          // textual datetime description into a Unix timestamp
            $timestamp = strtotime("-$i month");
          // 'n' numeric representation of a month -1, without leading zeros
            $value =  date('n', strtotime("-$i month"));
          // a full textual representation of a month -1
            $text  =  date('F', strtotime("-$i month"));
            $monthYears[$j] = date("Y-m", strtotime(date('Y-m-01')." -$i months"));
            $monthsNumber[$j] = $value;
            $months[$j] = $text;
            $j--;
        }
			
        $dt = Carbon::now();
        $dt = $dt->subMonths(12);
        $startDate = $dt;
        $endDate = Carbon::now();
        $data['totalNights'] = $this->report->getNights($startDate, $endDate);       

        $data['totalIncome'] = number_format($this->report->getIncomes($startDate, $endDate), 2, '.', ',');
        $data['totalReservations'] = $this->report->getReservations($startDate, $endDate);
        for ($i=0; $i <count($monthYears); $i++) {
            $startDate     = new Carbon('first day of '.$monthYears[$i]);
            $endDate       = new Carbon('last day of '.$monthYears[$i]);
            $monthlyNights[$i] = (int) $this->report->getNights($startDate, $endDate);
        }

        $data['months']   = json_encode($months);
        $data['monthlyNights'] = json_encode($monthlyNights);
        
        return view('admin.dashboard', $data);
    }
}
