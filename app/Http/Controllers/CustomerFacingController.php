<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CustomerFacingController extends Controller
{
    //

    public function dashboard()
    {
        return Inertia::render('Customers/Facing/Dashboard');
    }

    public function terms(Request $request): RedirectResponse
    {

        return Redirect::route('customer.dashboard');

    }

    public function privacy(Request $request): RedirectResponse
    {
        return Redirect::route('customer.dashboard');
    }

    public function privacyPolicy(Request $request): RedirectResponse
    {
        return Redirect::route('customer.dashboard');
    }

    public function prospectivePrivacy()
    {
        return Inertia::render('Prospective/PrivacyPolicy');
    }

    public function termSigning()
    {

//        dd('termSigning');

        return Inertia::render('Prospective/Onboarding');
    }

}
