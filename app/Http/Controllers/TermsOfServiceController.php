<?php

namespace App\Http\Controllers;

use App\Models\OneTimePasscode;
use Inertia\Inertia;

class TermsOfServiceController extends Controller
{
    //

    public function serviceFee($code)
    {
        // Retrieve the user_id from the one_time_codes table
        $oneTimeCode = OneTimePasscode::where('one_time_code', $code)->first();

        // Check if the code exists
        if (! $oneTimeCode) {
            return redirect()->route('error.page')->with('error', 'Invalid code or code expired.');
        }

        // Return Inertia render with the code
        return Inertia::render('TermsOfService/ServiceFee', [
            'customerId' => $oneTimeCode->one_time_code,
        ]);
    }

    public function accept() {}
}
