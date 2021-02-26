<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show(Request $request)
    {
        if (!auth()->check()) {
            return view('login-view');
        }

        return view('projects-view');
    }
}
