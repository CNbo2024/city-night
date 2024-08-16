<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    public function index()
    {
        $title = 'Tips';
        $tips = Tip::get();
        return view('tips.index-user', compact('tips', 'title'));
    }
}
