<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use App\Models\SpaceType;
use Illuminate\Http\Request;
use Session;

class WebScrapingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            dd($request->all());
        }

        $current_lang = Session::get('language');
        $data['property_type'] = PropertyType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');
        $data['space_type']    = SpaceType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');

        $data['title'] = 'Web scraping';

        return view('web-scraping.index', $data);
    }
}
