<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SettingsController extends Controller
{
    //

    public function index()
    {

        //        dd('Index');

        return Inertia::render('Settings/Index');
    }
}
