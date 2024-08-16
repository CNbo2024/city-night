<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\Settings;
use Illuminate\Http\Request;

class MinRequestController extends Controller
{
    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            Settings::updateOrCreate(
                ['name' => 'min-amount-for-payout-request'],
                ['value' => $request->amount]
            );

            $this->helper->one_time_message('success', 'Added Successfully');

            return redirect('admin/min-request');
        }

        $amount = Settings::where('name', 'min-amount-for-payout-request')->first()->value;

        return view('min-request.index', compact('amount'));
    }
}
