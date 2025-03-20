<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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

    /**
     * Show a list of service stops.
     *
     * @return \Inertia\Response
     */
    public function serviceStops()
    {

        // Get the authenticated user
        $authUser = auth()->user();

        // Step 1: Fetch all service stops for the authenticated user's customer ID
        $serviceStops = ServiceStop::where('customer_id', $authUser->id)
            ->select('id', 'user_id', 'time_in', 'time_out', 'chlorine_level', 'ph_level')
            ->get();

        // Step 2: Initialize an array to store unique pool guy data
        $poolGuys = [];

        // Step 3: Transform service stops and add a 'contact' column
        $transformedServiceStops = $serviceStops->map(function ($stop) use (&$poolGuys) {
            // Fetch pool guy information
            $poolGuy = User::find($stop->user_id);

            if ($poolGuy) {
                if ($poolGuy->active) {
                    // If the pool guy is active
                    $contact = $poolGuy->id;

                    // Add the pool guy to the array if not already present
                    if (! array_key_exists($poolGuy->id, $poolGuys)) {
                        $poolGuys[$poolGuy->id] = [
                            'id' => $poolGuy->id,
                            'name' => $poolGuy->name,
                            'email' => $poolGuy->email,
                            'active' => $poolGuy->active,
                            // Add any additional fields as needed
                        ];
                    }
                } else {
                    // If the pool guy is inactive
                    $contact = 2;
                }
            } else {
                // If the pool guy does not exist
                $contact = 2;
            }

            // Add the 'contact' column to the service stop data
            return [
                'id' => $stop->id,
                'user_id' => $stop->user_id,
                'time_in' => $stop->time_in,
                'time_out' => $stop->time_out,
                'chlorine_level' => $stop->chlorine_level,
                'ph_level' => $stop->ph_level,
                'contact' => $contact,
            ];
        });

        // Step 4: Fetch customer information for the authenticated user
        $customerInfo = Customer::where('id', $authUser->id)->first();

        // Step 5: Prepare the Inertia response
        return Inertia::render('Customer/Facing/ServiceStops', [
            'customerInfo' => $customerInfo,
            'serviceStops' => $transformedServiceStops,
            'poolGuys' => array_values($poolGuys), // Convert to indexed array
        ]);

        //        // Fetch data to pass to the Inertia view (e.g., from a database or API)
        //        $serviceStops = [
        //            ['id' => 1, 'name' => 'Stop 1', 'description' => 'Service stop description 1'],
        //            ['id' => 2, 'name' => 'Stop 2', 'description' => 'Service stop description 2'],
        //        ];
        //
        //        // Return the Inertia Vue component with data
        //        return Inertia::render('Customers/Facing/ServiceStops', [
        //            'serviceStops' => $serviceStops,
        //        ]);
    }

    /**
     * Show details for a single service stop.
     *
     * @return \Inertia\Response
     */
    public function serviceStop(Request $request)
    {
        // Fetch a specific service stop, using the `id` passed in the request (example data)
        $serviceStop = [
            'id' => $request->query('id', 1), // Default to 1 if no ID provided
            'name' => 'Stop 1',
            'description' => 'Detailed description of service stop 1',
        ];

        // Return the Inertia Vue component with the specific service stop data
        return Inertia::render('Customers/Facing/ServiceStop', [
            'serviceStop' => $serviceStop,
        ]);
    }
}
