<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestingController extends Controller
{
    //

    public function draggable()
    {
        return Inertia::render('Testing/Draggable');
    }

}
