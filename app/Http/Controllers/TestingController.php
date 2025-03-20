<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TestingController extends Controller
{
    //

    public function draggable()
    {
        return Inertia::render('Testing/Draggable');
    }

    public function cloudinary()
    {
        return Inertia::render('Testing/Cloudinary');
    }
}
