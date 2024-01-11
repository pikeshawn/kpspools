<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BillingController extends Controller
{
    //

    public function setup()
    {
        return Inertia::render('Billing/Setup');
    }
}
