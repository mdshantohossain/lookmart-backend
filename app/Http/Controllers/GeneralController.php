<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function about()
    {
        return view('website.about.index');
    }

    public function contact()
    {
        return view('website.contact.index');
    }
}
