<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        #session()->forget('area');
        $areas = Area::get()->toTree();
        #dd($areas); // dumps the raw data
        return view('home', compact('areas'));
    }
}
