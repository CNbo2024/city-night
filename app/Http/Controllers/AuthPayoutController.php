<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\PayoutSetting;
use App\Models\Settings;
use DB;
use Illuminate\Http\Request;

class AuthPayoutController extends Controller
{
    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::table('automatic_payouts')->updateOrInsert(
                ['user_id' => auth()->user()->id],
                ['value' => $request->amount, 'payment_method' => $request->payment_method]
            );

            $this->helper->one_time_message('success', 'Added Successfully');

            return redirect('users/auth-payout');
        }

        $payouts = PayoutSetting::with('payment_methods')->where(['user_id' => auth()->user()->id])->get();

        $title  = 'Setup automatic payout requests';
        $amount = DB::table('automatic_payouts')->where('user_id', auth()->user()->id)->first();

        return view('auth-payout.index', compact('amount', 'title', 'payouts'));
    }
}
