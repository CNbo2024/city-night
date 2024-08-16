<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use DB;

class PropertyPriceDateController extends Controller
{
	protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

	public function get()
	{
		$price = DB::table('properties_prices_date')
			->where('date', request()->date)
			->where('property_id', request()->property_id)
			->first();

		$this->helper->one_time_message('success', 'Special discount deleted successfully!');

		return redirect('/calendar');

		return $price->price ?? null;
	}

	public function delete()
	{
		DB::table('properties_prices_date')
			->where('date', request()->date)
			->where('property_id', request()->property_id)
			->whereNotNull('special')
			->delete();
	}

	public function save()
	{
		if (request()->dates) {
			$dates = json_decode(request()->dates);
		} else {
			$dates[] = request()->date;
		}

		if (request()->special) {
			$special = request()->special;
		} else {
			$special = '';
		}

		foreach ($dates as $date) {
			$price = DB::table('properties_prices_date')
				->where('property_id', request()->property_id)
				->where('date', $date)
				->where('special', $special)
				->first();

			if ($price) {
				$price = DB::table('properties_prices_date')
					->where('property_id', request()->property_id)
					->where('date', $date)
					->where('special', $special)
					->update(['price' => request()->price]);

			} else {
				DB::table('properties_prices_date')
					->insert([
						'date' => $date,
						'price' => request()->price,
						'special' => request()->special,
						'property_id' => request()->property_id,
					]);
			}

			if (request()->week) {
				DB::table('properties')
					->where('id', request()->property_id)
					->update(['week' => request()->week]);
			}

			if (request()->month) {
				DB::table('properties')
					->where('id', request()->property_id)
					->update(['month' => request()->month]);
			}			
		}

		$this->helper->one_time_message('success', 'Price created/updated successfully!');

		return redirect('/calendar');
	}
}
