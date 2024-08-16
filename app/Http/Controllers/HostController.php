<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Currency;
use App\Models\Properties;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Wallet;
use DB;

class HostController extends Controller
{
	public function index()
	{
		$user = User::find(auth()->user()->id);
		$user->host = 1;
		$user->save();

		if (! auth()->user()->host) {
			return redirect('/hosts');
		}

		$seven = ((new DateTime())->modify('+7day'))->format('Y-m-d');

		$checkout = Bookings::whereDate('end_date', now()->format('Y-m-d'))->get();

		$progress = Bookings::whereDate('start_date', '>=', now()->format('Y-m-d'))
			->whereDate('end_date', '<=', now()->format('Y-m-d'))
			->get();

		$soon = Bookings::whereDate('start_date', '>=', now()->format('Y-m-d'))
			->whereDate('end_date', '<=', $seven)
			->get();

		$scheduled = Bookings::whereDate('start_date', '>', $seven)->get();

		$unreviews = Bookings::whereNotIn('id', function ($query) {
			$query->select('id')->from('reviews');
		})->get();

		return view('host.index', compact('checkout', 'progress', 'soon', 'scheduled', 'unreviews'));
	}

	public function calendar()
	{
		$wallet = Wallet::where('user_id', auth()->user()->id)->first();

		$data['currency'] = Currency::find($wallet->currency_id);

		$data['properties'] = Properties::where('host_id', auth()->user()->id)->get();

		$events = DB::table('bookings')
			->leftJoin('users', 'users.id', '=', 'bookings.user_id')
			->leftJoin('properties', 'properties.id', '=', 'bookings.property_id')
			->select(DB::raw("properties.color, bookings.id, CONCAT(properties.id, '<br>', users.first_name, ' ', users.last_name) AS title, bookings.start_date AS start"))
			->where('bookings.host_id', auth()->user()->id)
			->get();

		$data['events'] = json_encode($events);

		$data['data'] = $this->getPricingArrayByDate(request()->property_id);

		$data['blocked'] = json_encode(DB::table('blocked_dates')->where('user_id', auth()->user()->id)->get());

		if (request()->property_id) {
			$items = DB::table('properties_prices_date')->whereNotNull('special')->get();

			foreach ($items as $item) {
				$data['special'][$item->date] = $item->special;
			}
		}

		return view('host.calendar', $data);
	}

	public function getPricingArrayByDate($property_id)
	{
		if ($property_id) {
			$start = Carbon::now()->startOfMonth()->modify('-1year')->format('Y-m-d');
			$end = Carbon::now()->endOfMonth()->modify('+1year')->format('Y-m-d');

			$array = [];

			while ($start != $end) {
				$price = DB::table('properties_prices_date')
					->where('property_id', $property_id)
					->where('date', $start)
					->first();

				if ($price) {
					$array[$start] = (float) $price->price;
				} else {
					$property = Properties::find($property_id);
					$array[$start] = $property->property_price->price;
				}

				$start = new DateTime($start);
				$start->modify('+1day');
				$start = $start->format('Y-m-d');
			}

			return $array;			
		}
	}
}
