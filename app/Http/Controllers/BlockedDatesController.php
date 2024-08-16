<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use DB;

class BlockedDatesController extends Controller
{
	protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }
    
	public function save()
	{
		foreach (json_decode(request()->dates) as $date) {
			DB::table('blocked_dates')
				->insert(['date' => $date, 'user_id' => auth()->user()->id]);
		}

		$this->helper->one_time_message('success', 'Dates blocked successfully!');
	}

	public function update()
	{
		DB::table('blocked_dates')
			->where('date', request()->date)
			->where('user_id', auth()->user()->id)
			->delete();

		$this->helper->one_time_message('success', 'Date unlocked successfully!');

		return redirect('/calendar');
	}
}
