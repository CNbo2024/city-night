<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index()
    {
        $tips = Tip::get();
        return view('tips.index', compact('tips'));
    }

    public function create()
    {
        return view('tips.create');
    }

    public function store(Request $request)
    {
        Tip::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $this->helper->one_time_message('success', trans('Tip created successfully!'));

        return redirect('/admin/tips');
    }

    public function edit($id)
    {
        $tip = Tip::find($id);
        return view('tips.edit', compact('tip'));
    }

    public function update(Request $request, $id)
    {
        $tip = Tip::find($id);
        $tip->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $this->helper->one_time_message('success', trans('Tip updated successfully!'));

        return redirect('/admin/tips');
    }

    public function destroy($id)
    {
        Tip::find($id)->delete();
        $this->helper->one_time_message('success', trans('Tip deleted successfully!'));
        return redirect('/admin/tips');
    }
}
