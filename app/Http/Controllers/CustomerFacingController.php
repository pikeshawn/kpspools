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


        if (!Auth::user()->terms_and_conditions) {

            if ($request->terms) {
                Auth::user()->terms_and_conditions = true;
                $date = Carbon::now();
                Auth::user()->terms_accepted_date = $date->toDateTimeString();
                Auth::user()->save();
            }

        }

        if (!Auth::user()->privacy_policy) {
            return Redirect::route('prospective.privacy');
        }

        if (!Auth::user()->stripe_id) {
            return Redirect::route('setup');
        }

        return Redirect::route('customer.dashboard');

    }

    public function privacy(Request $request): RedirectResponse
    {

        if (!Auth::user()->privacy_policy) {

            if ($request->privacy) {
                Auth::user()->privacy_policy = true;
                $date = Carbon::now();
                Auth::user()->privacy_policy_accepted_date = $date->toDateTimeString();
                Auth::user()->save();
            }

        }


        if (!Auth::user()->terms) {
            return Redirect::route('prospective.privacy');
        }

        if (!Auth::user()->stripe_id) {
            return Redirect::route('setup');
        }

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
