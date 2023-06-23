<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function statistic()
    {
        return view('example.statistic');
    }

    public function landing()
    {
        return view('example.landing');
    }

    public function landing2()
    {
        return view('example.landing2');
    }
}
