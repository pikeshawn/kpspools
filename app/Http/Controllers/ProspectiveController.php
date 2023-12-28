<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProspectiveController extends Controller
{
    //
    public function index()
    {
//        dd(Auth::user());
        if (Auth::user()->type === 'prospective') {
            return Inertia::render('Prospective/Landing');
        } else if (Auth::user()->type === 'serviceman') {
            return redirect('dashboard');
        }
    }
}
