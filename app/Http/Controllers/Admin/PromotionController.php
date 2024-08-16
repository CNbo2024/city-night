<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index()
    {
        $promotions = Promotion::get();
        return view('promotion.index', compact('promotions'));
    }

    public function create()
    {
        return view('promotion.create');
    }

    public function store(Request $request)
    {
        Promotion::create([
            'code' => $request->code,
            'discount' => $request->discount,
        ]);

        $this->helper->one_time_message('success', trans('Promotion created successfully!'));

        return redirect('/admin/promotion');
    }

    public function edit($id)
    {
        $promotion = Promotion::find($id);
        return view('promotion.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);
        $promotion->update([
            'code' => $request->code,
            'discount' => $request->discount,
        ]);

        $this->helper->one_time_message('success', trans('Promotion updated successfully!'));

        return redirect('/admin/promotion');
    }

    public function destroy($id)
    {
        Promotion::find($id)->delete();
        $this->helper->one_time_message('success', trans('Promotion deleted successfully!'));
        return redirect('/admin/promotion');
    }

    public function list($id)
    {
        $promotion = Promotion::find($id);

        $bookings = Booking::where('promotion', $promotion->code)->get();

        return view('promotion.list', compact('promotion'));
    }
}
