<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {
    public function home() {
        // return redirect()->route('dashboard');
        return view('content.landing.index');
    }

    public function dashboard() {
        return view('content.dashboard.index');
    }
}
